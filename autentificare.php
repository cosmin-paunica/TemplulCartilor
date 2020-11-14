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

        if (isset($_GET["err"])) {
            $err = $_GET["err"];
            if ($err == "incomplet")
                echo '<p class="err-text">Datele introduse sunt incomplete.</p>';
            else if ($err == "nuexista")
                echo '<p class="err-text">Nu există un cont înregistrat cu această adresă de email.</p>';
            else if ($err == "parolagresita")
                echo '<p class="err-text">Parola introdusă este greșită.</p>';
        }

        ?>

        <form action="_inc/autentificare.inc.php" method="POST">
            <table class="form-table">
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td><input type="email" name="email" /></td>
                </tr>
                <tr>
                    <td><label for="parola">Parola:</label></td>
                    <td><input type="password" name="parola" /></td>
                </tr>
                <!-- <tr>
                    <td><label for="ramaiaut">Rămâi autentificat?</label></td>
                    <td><input type="checkbox" name="ramaiaut" /></td>
                </tr> -->
                <tr>
                    <td colspan="2"><input type="submit" value="Autentifică-te" /></td>
                </tr>
            </table>
        </form>
    </body>
</html>
