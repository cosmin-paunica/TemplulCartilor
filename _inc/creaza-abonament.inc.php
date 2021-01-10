<?php

header('Content-Type: text/html; charset=utf-8');
session_start();
require "../_inc/conexiune_bd.inc.php";

if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ["bibliotecar", "admin"]))
    header("Location: ../index.php");
else {
    // tratare formular modificat sau campuri necompletate
    $campuri = ["email-client", "data-inceput", "durata"];
    $complet = true;
    foreach ($campuri as $camp)
        if (!isset($_POST[$camp]) || empty(trim($_POST[$camp]))) {
            $complet = false;
            header("Location: ../actiuni/creaza-abonament?err=incomplet");
        }

    if ($complet) {
        // preluare date
        $email_client = addslashes(trim($_POST["email-client"]));
        $data_inceput = date_create($_POST["data-inceput"]);
        $str_data_inceput = date_format($data_inceput, "Y-m-d");
        $durata = $_POST["durata"];
        if ($durata == "1-luna")
            $de_adaugat = "1 month";
        else if ($durata == "6-luni")
            $de_adaugat = "6 months";
        else if ($durata == "2-ani")
            $de_adaugat = "2 years";
        $data_expirare = date_add($data_inceput, date_interval_create_from_date_string($de_adaugat));
        $str_data_expirare = date_format($data_expirare, "Y-m-d");

        // obtinere id utilizator
        $interog = $bd->prepare("SELECT id_utilizator FROM utilizatori WHERE email=?");
        $interog->bind_param("s", $email_client);
        $interog->execute();
        $rez = $interog->get_result();
        if ($rez->num_rows == 0)
            header("Location: ../actiuni/creaza-abonament?err=nuexista");
        else {
            $linie = $rez->fetch_object();
            $id_utilizator = $linie->id_utilizator;

            // inserare in tabela abonamente
            $interog = $bd->prepare("
                INSERT INTO abonamente (id_utilizator, data_inceput, data_expirare)
                VALUES (?, ?, ?)
            ");
            $interog->bind_param("iss", $id_utilizator, $str_data_inceput, $str_data_expirare);
            $interog->execute();

            header("Location: ../actiuni/creaza-abonament?msg=creat");
        }
    }
}

?>
