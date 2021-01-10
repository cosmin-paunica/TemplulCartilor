<?php

require "../_inc/conexiune_bd.inc.php";

session_start();

if (!isset($_SESSION["id_utilizator"]))
    header("Location: ../");
else {
    // verificare campuri complete
    $campuri = ["parola-veche", "parola-noua", "conf-parola-noua"];
    $complet = true;
    foreach ($campuri as $camp)
        if (!isset($_POST[$camp]) || empty(trim($_POST[$camp]))) {
            $complet = false;
            header("Location: ../profil/schimba-parola?err=incomplet");
        }
    
    if ($complet) {
        $id_utilizator = $_SESSION["id_utilizator"];
        $parola_veche = trim($_POST["parola-veche"]);
        
        // selectare date din bd
        $interog = $bd->prepare("SELECT parola FROM utilizatori WHERE id_utilizator = ?");
        $interog->bind_param("i", $id_utilizator);
        $interog->execute();

        $rez = $interog->get_result();
        $linie = $rez->fetch_object();
        $parola_bd = $linie->parola;

        $parola_veche_cript = hash("sha256", $parola_veche);
        if ($parola_veche_cript != $parola_bd)
            header("Location: ../profil/schimba-parola?err=pgresita");
        else {
            $parola_noua = trim($_POST["parola-noua"]);
            $conf_parola_noua = trim($_POST["conf-parola-noua"]);
            
            if ($parola_noua != $conf_parola_noua)
                header("Location: ../profil/schimba-parola?err=diferite");
            else {
                $parola_noua_cript = hash("sha256", $parola_noua);

                $interog = $bd->prepare("UPDATE utilizatori SET parola=? WHERE id_utilizator=?");
                $interog->bind_param("si", $parola_noua_cript, $id_utilizator);
                $interog->execute();

                header("Location: ../profil/schimba-parola?msg=schimbata");
            }
        }
    }
}

?>
