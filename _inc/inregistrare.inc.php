<?php

session_start();
require "../_setup/conexiune_bd.php";

$campuri = ["email", "prenume", "nume", "parola", "conf-parola"];
$complet = true;
foreach ($campuri as $camp)
    if (!isset($_POST[$camp]) || empty(trim($_POST[$camp])))
        $complet = false;

if (!$complet)
    header("Location: ../inregistrare.php?err=incomplet");

$email = trim($_POST["email"]);
$prenume = trim($_POST["prenume"]);
$nume = trim($_POST["nume"]);
$parola = trim($_POST["parola"]);
$conf_parola = trim($_POST["conf-parola"]);

if ($parola != $conf_parola)
    header("Location: ../inregistrare.php?err=pdiferite");

$parola_criptata = hash('sha256', $parola);

$interog = $bd->prepare("SELECT email FROM utilizatori WHERE email=?");
$interog->bind_param("s", $email);
$interog->execute();
$nr_randuri = $interog->get_result()->num_rows;
$interog->close();
if ($nr_randuri > 0)
    header("Location: ../inregistrare.php?err=exista");

$interog = $bd->prepare("
    INSERT INTO utilizatori (email, prenume, nume, parola) 
    VALUES (?, ?, ?, ?)
");
$interog->bind_param("ssss", $email, $prenume, $nume, $parola_criptata);
$interog->execute();
$interog->close();

header("Location: ../index.php?info=inregistrat");

?>