<?php

session_start();
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_functii/functii.php";
require_once "../_inc/setup.inc.php";
require "../_clase/PHPMailerAutoload.php";

// recaptcha
$response_key = $_POST["g-recaptcha-response"];
$user_ip = $_SERVER["REMOTE_ADDR"];
if (!rezultat_recaptcha($response_key, $user_ip))
    header("Location: ../inregistrare?err=nebifat");
else {
    $campuri = ["email", "prenume", "nume", "parola", "conf-parola"];
    $complet = true;
    foreach ($campuri as $camp)
        if (!isset($_POST[$camp]) || empty(trim($_POST[$camp])))
            $complet = false;

    if (!$complet)
        header("Location: ../inregistrare.php?err=incomplet");
    else {
        $email = htmlspecialchars(trim($_POST["email"]));
        $prenume = htmlspecialchars(trim($_POST["prenume"]));
        $nume = htmlspecialchars(trim($_POST["nume"]));
        $parola = trim($_POST["parola"]);
        $conf_parola = trim($_POST["conf-parola"]);

        if ($parola != $conf_parola)
            header("Location: ../inregistrare.php?err=pdiferite");
        else {
            $parola_criptata = hash('sha256', $parola);

            $interog = $bd->prepare("SELECT email FROM utilizatori WHERE email=?");
            $interog->bind_param("s", $email);
            $interog->execute();
            $nr_randuri = $interog->get_result()->num_rows;
            $interog->close();
            if ($nr_randuri > 0)
                header("Location: ../inregistrare.php?err=exista");
            else {
                $token = genereaza_token();

                // trimitere email de validare
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->SMTPAuth   	= true;
                    $mail->SMTPSecure 	= "tls";
                    $mail->Host       	= 'smtp.gmail.com';
                    $mail->Port       	= 587;
                    $mail->Username   	= 'templulcartilor@gmail.com';
                    $mail->Password		= 'templu123';
                    $mail->SMTPDebug 	= 2;
                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ];
                
                    $mail->setFrom('templulcartilor@gmail.com');
                    $mail->addAddress($email);
                
                    $mail->isHTML(true);
                    $mail->Subject = "Validare cont pe Templul Cartilor";
                    $mail->Body    = "Stimata/stimate ".$prenume." ".$nume.",<br/><br/>Bine ati venit la Templul Cartilor! Click <a href=".ROOT."_inc/validare.inc.php?email=".$email."&token=".$token.">aici</a> pentru a va valida contul.";
                
                    $mail->send();
        
                    header("Location: ../contact?msg=reusit");
                } catch (Exception $e) {
                    header("Location: ../contact?err=eroare-email");
                }

                $interog = $bd->prepare("
                    INSERT INTO utilizatori (email, prenume, nume, parola, token) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $interog->bind_param("sssss", $email, $prenume, $nume, $parola_criptata, $token);
                $interog->execute();
                $interog->close();

                header("Location: ../?msg=inregistrat");
            }
        }
    }
}

?>
