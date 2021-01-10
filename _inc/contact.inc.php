<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

require "../_clase/PHPMailerAutoload.php";
require "../_functii/functii.php";
require "parola_email.inc.php";

session_start();

// recaptcha
$response_key = $_POST["g-recaptcha-response"];
$user_ip = $_SERVER["REMOTE_ADDR"];
if (!rezultat_recaptcha($response_key, $user_ip))
    header("Location: ../contact?err=nebifat");
else {
    if (!isset($_SESSION["id_utilizator"]))
        $campuri = ["prenume", "nume", "email", "mesaj"];
    else
        $campuri = ["mesaj"];

    $complet = true;
    foreach ($campuri as $camp)
        if (!isset($_POST[$camp]) || empty($_POST[$camp]))
            $complet = false;

    if (!$complet)
        header("Location: ../contact?err=incomplet");
    else {
        if (isset($_SESSION["id_utilizator"])) {
            $prenume = $_SESSION["prenume"];
            $nume = $_SESSION["nume"];
            $email = $_SESSION["email"];
        } else {
            $prenume = $_POST["prenume"];
            if (!ctype_alpha($prenume))
                $invalid = "prenume";
            $nume = $_POST["nume"];
            if (!ctype_alpha($nume))
                $invalid = "nume";
            $email = $_POST["email"];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalid = "email";
                echo $email; die;
            }
        }

        if (isset($invalid))
            header("Location: ../contact?err=invalid&invalid=".$invalid);
        else {
            $mesaj = htmlspecialchars($_POST["mesaj"]);
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->SMTPAuth   	= true;
                $mail->SMTPSecure 	= "tls";
                $mail->Host       	= 'smtp.gmail.com';
                $mail->Port       	= 587;
                $mail->Username   	= 'templulcartilor@gmail.com';
                $mail->Password		= $parola_email;
                $mail->SMTPDebug 	= 2;
                $mail->SMTPOptions = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ];
            
                $mail->setFrom('templulcartilor@gmail.com');
                $mail->addAddress("templulcartilor@gmail.com");
            
                $mail->isHTML(true);
                if (isset($_SESSION["id_utilizator"]))
                    $mail->Subject = "Mesaj de contact de la utilizatorul ".$email;
                else
                $mail->Subject = "Mesaj de contact de la vizitatorul ".$email;
                $mail->Body    = "Nume si prenume: ".$prenume." ".$nume."<br/>Email: ".$email."<br/><br/>Mesaj:<br/>".$mesaj;
            
                $mail->send();

                header("Location: ../contact?msg=reusit");
            } catch (Exception $e) {
                header("Location: ../contact?err=eroare-email");
            }
        }
    }
}

?>