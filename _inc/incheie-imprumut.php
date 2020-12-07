<?php

require_once "../_inc/conexiune_bd.inc.php";

session_start();
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ["bibliotecar", "admin"]))
    header("Location: ../index.php?err=interzis");
else {
    if (!isset($_GET["id_utilizator"]) || !is_numeric($_GET["id_utilizator"]) || !isset($_GET["id_carte"]) || !is_numeric($_GET["id_carte"]))
        header("Location: ../actiuni/gestioneaza-imprumuturi?err=invalid");
    else {
        $id_utilizator = $_GET["id_utilizator"];
        $id_carte = $_GET["id_carte"];

        $bd->query("
            UPDATE imprumuturi
            SET predat=1
            WHERE id_utilizator='$id_utilizator'
            AND id_carte='$id_carte'
            AND predat=0
        ");

        if ($bd->affected_rows == 0)
            header("Location: ../actiuni/gestioneaza-imprumuturi?err=inexistent");
        else
            header("Location: ../actiuni/gestioneaza-imprumuturi?msg=incheiat");
    }
}

?>
