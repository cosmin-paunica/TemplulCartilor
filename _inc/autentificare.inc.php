<?php

session_start();
require "../_inc/conexiune_bd.inc.php";
require "../_functii/functii.php";

// recaptcha
$response_key = $_POST["g-recaptcha-response"];
$user_ip = $_SERVER["REMOTE_ADDR"];
if (!rezultat_recaptcha($response_key, $user_ip))
    header("Location: ../autentificare?err=nebifat");
else {
    $campuri = ["email", "parola"];
    $complet = true;
    foreach ($campuri as $camp)
        if (!isset($_POST[$camp]) || empty(trim($_POST[$camp])))
            $complet = false;

    if (!$complet)
        header("Location: ../autentificare.php?err=incomplet");
    else {
        $email = htmlspecialchars(trim($_POST["email"]));
        $parola = trim($_POST["parola"]);

        $interog = $bd->prepare("SELECT * FROM utilizatori WHERE email=?");
        $interog->bind_param("s", $email);
        $interog->execute();
        $rez = $interog->get_result();

        if ($rez->num_rows == 0)
            header("Location: ../autentificare.php?err=nuexista");
        else {
            $parola_criptata = hash("sha256", $parola);
            $linie = $rez->fetch_assoc();
            $parola_bd = $linie["parola"];

            if ($parola_criptata != $parola_bd)
                header("Location: ../autentificare.php?err=parolagresita");
            else {
                if ($linie["rol"] == "nevalidat")
                    header("Location: ../autentificare.php?err=nevalidat");
                else {
                    $_SESSION["id_utilizator"] = $linie["id_utilizator"];
                    $_SESSION["email"] = $linie["email"];
                    $_SESSION["prenume"] = $linie["prenume"];
                    $_SESSION["nume"] = $linie["nume"];

                    if ($linie['rol'] == 'simplu') {
                        $interog = $bd->prepare("SELECT * FROM abonamente WHERE id_utilizator=? AND data_expirare > DATE(NOW())");
                        $interog->bind_param("i", $linie["id_utilizator"]);
                        $interog->execute();
                        $rez = $interog->get_result();

                        if ($rez->num_rows > 0) {
                            $_SESSION["rol"] = "client";
                        } else {
                            $_SESSION["rol"] = "simplu";
                        }
                    } else {
                        $_SESSION["rol"] = $linie["rol"];
                    }

                    header("Location: ../");
                }
            }
        }
    }
}

?>
