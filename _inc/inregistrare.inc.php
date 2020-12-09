<?php

session_start();
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_functii/functii.php";
require_once "../_inc/setup.inc.php";
require '../_phpmailer/PHPMailerAutoload.php';

$campuri = ["email", "prenume", "nume", "parola", "conf-parola"];
$complet = true;
foreach ($campuri as $camp)
    if (!isset($_POST[$camp]) || empty(trim($_POST[$camp])))
        $complet = false;

if (!$complet)
    header("Location: ../inregistrare.php?err=incomplet");
else {
    $email = addslashes(trim($_POST["email"]));
    $prenume = addslashes(trim($_POST["prenume"]));
    $nume = addslashes(trim($_POST["nume"]));
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
                $mail->Username   	= 'komma79259@gmail.com';
                $mail->Password		= 'analiza314';
                $mail->SMTPDebug 	= 2;
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
            
                $mail->setFrom('komma79259@gmail.com');
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

?>
