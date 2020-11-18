<?php

session_start();
if (!isset($_SESSION["id_utilizator"]))
    header("Location: ../");
require "conexiune_bd.inc.php";

$id_utilizator = $_SESSION["id_utilizator"];
$interog = $bd->prepare("DELETE FROM utilizatori WHERE id_utilizator=?");
$interog->bind_param("i", $id_utilizator);
$interog->execute();

session_destroy();
header("Location: ../?msg=contsters");

?>
