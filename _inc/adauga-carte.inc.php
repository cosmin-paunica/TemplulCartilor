<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
require "../_inc/conexiune_bd.inc.php";

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin")
    header("../index.php");

// tratare formular modificat
$campuri = ["titlu", "autori", "limba", "limba-noua", "data-pub", "nr-exemplare", "nr-pag", "serie", "serie-noua", "goodreads"];
foreach ($campuri as $camp)
    if (!isset($_POST[$camp]))
        header("Location: ../actiuni/adauga-carte.php?err=modificat");

// if (!isset($_FILES["fisier-img"]))
//     header("Location: ../carti/adauga-carte.php?err=modificat");

// tratare campuri obligatorii necompletate
$campuri = ["titlu", "autori", "nr-exemplare"];
foreach ($campuri as $camp)
    if (empty(trim($_POST["titlu"])))
        header("Location: ../actiuni/adauga-carte.php?err=incomplet");

if (empty(trim($_POST["limba"]) ) && empty(trim($_POST["limba-noua"])) )
    header("Location: ../actiuni/adauga-carte.php?err=incomplet");

// preluare date
$titlu = trim($_POST["titlu"]);
$autori = explode(",", $_POST["autori"]);
for ($i = 0; $i < count($autori); $i++)
    $autori[$i] = addslashes(trim($autori[$i]));
if (!empty(trim($_POST["limba-noua"])))
    $limba = addslashes(trim($_POST["limba-noua"]));
else
    $limba = addslashes(trim($_POST["limba"]));
if (!empty(trim($_POST["data-pub"])))
    $data_pub = addslashes(trim($_POST["data-pub"]));
if (!empty(trim($_POST["nr-exemplare"])))
    $nr_exemplare = addslashes(trim($_POST["nr-exemplare"]));
if (!empty(trim($_POST["nr-pag"])))
    $nr_pag = addslashes(trim($_POST["nr-pag"]));
if (!empty(trim($_POST["serie-noua"])))
    $serie = addslashes(trim($_POST["serie-noua"]));
else if (!empty(trim($_POST["serie"])))
    $serie = addslashes(trim($_POST["serie"]));
if (!empty(trim($_POST["goodreads"])))
    $goodreads = addslashes(trim($_POST["goodreads"]));

// fisier imagine
if (is_uploaded_file($_FILES['fisier-img']['tmp_name'])) {
    $fisier_img = $_FILES["fisier-img"];
    $nume_fisier_img = $fisier_img["name"];
    $nume_tmp_fisier_img = $fisier_img["tmp_name"];
    $ext = explode(".", $nume_fisier_img);
    $ext = strtolower(end($ext));
    
    if (!in_array($ext, ["jpg", "jpeg"]))
        header("Location: ../actiuni/adauga-carte.php?err=extimg");

    if ($fisier_img["error"] != 0)
        header("Location: ../actiuni/adauga-carte.php?err=imguplderr");

    $img = imagecreatefromjpeg($nume_tmp_fisier_img);
    // print_r(getimagesize($nume_tmp_fisier_img)); die;
    $l_orig = imagesx($img);
    $h_orig = imagesy($img);

    if ($l_orig > $h_orig) {
        $img = imagerotate($img, -90, 0);
        $l_orig = imagesx($img);
        $h_orig = imagesy($img);
    }

    $l_noua = 300;
    $h_noua = floor($h_orig / ($l_orig / $l_noua));
    $img_redim = imagecreatetruecolor($l_noua, $h_noua);
    imagecopyresampled($img_redim, $img, 0, 0, 0, 0, $l_noua, $h_noua, $l_orig, $h_orig);
    imagerotate($img_redim, -90, 0);
    imagejpeg($img_redim, $nume_tmp_fisier_img, 100);
    
    $nume_nou_img = uniqid("coperta1_".str_replace([" ", "'", "\""], "_", substr(str_replace([",", "."], "", $titlu), 0, 20))).".".$ext;
    $destinatie = "../_img/".$nume_nou_img;
    move_uploaded_file($nume_tmp_fisier_img, $destinatie);
}

// adaugare limba (daca nu exista), obtinere id_limba
$interog = $bd->prepare("SELECT * FROM limbi WHERE nume_limba=?");
$interog->bind_param("s", $limba);
$interog->execute();

$rez = $interog->get_result();
$limba_exista = false;
if ($rez->num_rows > 0)
    $limba_exista = true;
else {
    $interog = $bd->prepare("INSERT INTO limbi (nume_limba) VALUES (?)");
    $interog->bind_param("s", $limba);
    $interog->execute();
}

$interog = $bd->prepare("SELECT id_limba FROM limbi WHERE nume_limba=?");
$interog->bind_param("s", $limba);
$interog->execute();

$rez = $interog->get_result();
$linie = $rez->fetch_assoc();
$id_limba = $linie["id_limba"];

// adaugare serie (daca nu exista), obtinere id_serie
$interog = $bd->prepare("SELECT * FROM serii WHERE nume_serie=?");
$interog->bind_param("s", $serie);
$interog->execute();

$rez = $interog->get_result();
$serie_exista = false;
if ($rez->num_rows > 0)
    $serie_exista = true;
else {
    $interog = $bd->prepare("INSERT INTO serii (nume_serie) VALUES (?)");
    $interog->bind_param("s", $serie);
    $interog->execute();
}

$interog = $bd->prepare("SELECT id_serie FROM serii WHERE nume_serie=?");
$interog->bind_param("s", $serie);
$interog->execute();

$rez = $interog->get_result();
$linie = $rez->fetch_assoc();
$id_serie = $linie["id_serie"];

// inserare in tabela carti
$interog = $bd->prepare("
    INSERT INTO carti (titlu, id_limba, data_publicare, numar_pagini, fisier_imagine, id_serie, link_goodreads, numar_exemplare, numar_disponibile)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$interog->bind_param("sisisisii", $titlu, $id_limba, $data_pub, $nr_pag, $nume_nou_img, $id_serie, $goodreads, $nr_exemplare, $nr_exemplare);
$interog->execute();

// autori
$interog = $bd->prepare("SELECT id_carte FROM carti WHERE titlu=?");
$interog->bind_param("s", $titlu);
$interog->execute();

$rez = $interog->get_result();
$linie = $rez->fetch_assoc();
$id_carte = $linie["id_carte"];

foreach ($autori as $aut) {
    $interog = $bd->prepare("INSERT INTO autori_carti VALUES (?, ?)");
    $interog->bind_param("si", $aut, $id_carte);
    $interog->execute();
}

header("Location: ../actiuni/adauga-carte.php?msg=adaugata");

?>
