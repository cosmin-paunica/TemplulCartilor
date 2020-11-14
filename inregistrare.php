<?php

session_start();
require_once "_setup/setup.php";
require_once "_inc/topper.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/forme.css" />
        <title>Înregistrare</title>
    </head>
    <body>
        <?php require "_inc/header.inc.php"; ?>

        <?php

        if (isset($_GET["err"]) && !empty($_GET["err"])) {
            $err = $_GET["err"];
            if ($err == "incomplet")
                echo '<p class="err-text">Datele introduse sunt incomplete.</p>';
            else if ($err == "pdiferite")
                echo '<p class="err-text">Parolele introduse sunt diferite.</p>';
            else if ($err == "exista")
                echo '<p class="err-text">Deja există un cont înregistrat cu această adresă de email.</p>';
        }

        ?>

        <form action="_inc/inregistrare.inc.php" method="POST">
            <table class="form-table">
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td><input type="email" name="email" /></td>
                </tr>
                <tr>
                    <td><label for="prenume">Prenume:</label></td>
                    <td><input type="text" name="prenume" /></td>
                </tr>
                <tr>
                    <td><label for="nume">Nume:</label></td>
                    <td><input type="text" name="nume" /></td>
                </tr>
                <tr>
                    <td><label for="parola">Parolă:</label></td>
                    <td><input type="password" name="parola" /></td>
                </tr>
                <tr>
                    <td><label for="conf-parola">Confirmă parola:</label></td>
                    <td><input type="password" name="conf-parola" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Înregistrează-te" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>
