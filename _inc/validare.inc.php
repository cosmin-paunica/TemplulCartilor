<?php

require_once "conexiune_bd.inc.php";

session_start();

if (isset($_SESSION["id_utilizator"]))
    header("Location: ../index");
else {
    if (!isset($_GET["email"]) || !filter_var($_GET["email"], FILTER_VALIDATE_EMAIL) || !isset($_GET["token"]) || !ctype_alnum($_GET["token"]))
        header("Location: ../index?err=gresit");
    // if (!isset($_GET["email"]))
    //     header("Location: ../index?err=gresit1");
    // else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    //     header("Location: ../index?err=gresit2");
    // else if (!isset($_GET["token"]))
    //     header("Location: ../index?err=gresit3");
    // else if (!ctype_alnum($_GET["token"]))
    //     header("Location: ../index?err=gresit4");
    else {
        $email = $_GET["email"];
        $token = $_GET["token"];

        $interog = $bd->prepare("SELECT id_utilizator, rol, token FROM utilizatori WHERE email=?");
        $interog->bind_param("s", $email);
        $interog->execute();
        $rez = $interog->get_result();

        if ($rez->num_rows == 0)
            header("Location: ../index?err=gresit");
        else {
            $linie = $rez->fetch_object();
            if ($linie->rol != "nevalidat")
                header("Location: ../index?err=dejavalidat");
            else {
                $id_utilizator = $linie->id_utilizator;
                $bd->query("UPDATE utilizatori SET rol='simplu' WHERE id_utilizator='$id_utilizator'");

                header("Location: ../autentificare.php?msg=validat");
            }
        }
    }
}

?>