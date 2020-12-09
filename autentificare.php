<?php

session_start();
require_once "_inc/setup.inc.php";
require_once "_inc/topper.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="_css/forme.css" />
        <title>Autentificare • Templul Cărților</title>
    </head>
    <body>
        <?php require "_inc/header.inc.php"; ?>

        <main>
            
            <?php

            if (isset($_GET["err"])) {
                $err = $_GET["err"];
                if ($err == "incomplet")
                    echo '<p class="err-text">Datele introduse sunt incomplete.</p>';
                else if ($err == "nuexista")
                    echo '<p class="err-text">Nu există un cont înregistrat cu această adresă de email.</p>';
                else if ($err == "parolagresita")
                    echo '<p class="err-text">Parola introdusă este greșită.</p>';
                else if ($err == "nevalidat")
                    echo '<p class="err-text">Contul dumneavoastră nu a fost validat! Vă rugăm să verificați email-ul.</p>';
            }

            if (isset($_GET["msg"])) {
                $msg = $_GET["msg"];
                if ($msg == "validat") {
                    ?> <p class="succes-text">Contul dumneavoastră a fost validat! Introduceți mai jos datele de autentificare:</p> <?php
                }
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
        </main>
    </body>
</html>
