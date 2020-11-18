<?php

session_start();

if (!isset($_SESSION["id_utilizator"]))
    header("Location: ../");

require_once "../_inc/setup.inc.php";
require "../_inc/topper.inc.php";
require "../_inc/conexiune_bd.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="../_css/forme.css" />
        <title><?php echo $_SESSION["prenume"]." ".$_SESSION["nume"]; ?> • Templul Cărților</title>
    </head>
    <body>
        <?php require "../_inc/header.inc.php"; ?>
        <main>
            <?php

            if (isset($_GET["err"])) {
                $err = $_GET["err"];
                if ($err == "incomplet")
                    echo '<p class="err-text">Datele introduse sunt incomplete.</p>';
                else if ($err == "pgresita")
                    echo '<p class="err-text">Parola veche introdusă este greșită.</p>';
                else if ($err == "diferite")
                    echo '<p class="err-text">Parolele noi introduse nu sunt identice.</p>';
            }

            if (isset($_GET["msg"])) {
                $msg = $_GET["msg"];
                if ($msg == "schimbata")
                    echo '<p class="succes-text">Parola a fost schimbată cu succes!</p>';
            }

            ?>

            <form action="../_inc/schimba-parola.inc.php" method="POST">
                <table class="form-table">
                    <tr>
                        <td><label for="parola-veche">Parola veche:</label></td>
                        <td><input type="password" name="parola-veche" /></td>
                    </tr>
                    <tr>
                        <td><label for="parola-noua">Parola nouă:</label></td>
                        <td><input type="password" name="parola-noua" /></td>
                    </tr>
                    <tr>
                        <td><label for="conf-parola-noua">Confirmați parola nouă:</label></td>
                        <td><input type="password" name="conf-parola-noua" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="submit" value="Schimbă parola" /></td>
                    </tr>
                </table>
            </form>
        </main>
    </body>
</html>
