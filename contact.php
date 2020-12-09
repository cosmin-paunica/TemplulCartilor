<?php

session_start();
require_once "_inc/setup.inc.php";
require_once "_inc/topper.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/forme.css" />
		<title>Contact • Templul Cărților</title>
	</head>
	<body>
		<?php require "_inc/header.inc.php"; ?>
		<main>			
            <h1>Contact</h1>
            
			<?php

            if (isset($_GET["err"])) {
                $err = $_GET["err"];
                if ($err == "incomplet") {
                    ?> <p class="err-text">Unul dintre câmpurile obligatorii nu a fost completat!</p> <?php
                }
                else if ($err == "invalid") {
                    if ($invalid == "prenume") {
                        ?> <p class="err-text">Prenumele introdus este invalid!</p> <?php
                    }
                    else if ($invalid == "nume") {
                        ?> <p class="err-text">Numele introdus este invalid!</p> <?php
                    }
                    else if ($invalid == "email") {
                        ?> <p class="err-text">Email-ul introdus este invalid!</p> <?php
                    }
                }
                else if ($err == "eroare-email") {
                    ?> <p class="err-text">A apărut o eroare la trimiterea mesajului. Vă rugăm să încercați din nou.</p> <?php
                }
            }

            if (isset($_GET["msg"])) {
                $msg = $_GET["msg"];
                if ($msg == "reusit") {
                    ?> <p class="succes-text">Mesajul dumneavoastră a fost trimis!</p> <?php
                }
            }

            ?>

            <form action="_inc/contact.inc.php" method="POST">
                <table class="form-table">
                    <tbody>
                        <?php if (!isset($_SESSION["id_utilizator"])) { ?>
                            <tr>
                                <td><label>Prenume: </label></td>
                                <td><input type="text" name="prenume" /></td>
                            </tr>
                            <tr>
                                <td><label>Nume: </label></td>
                                <td><input type="text" name="nume" /></td>
                            </tr>
                            <tr>
                                <td><label>Email: </label></td>
                                <td><input type="email" name="email" /></td>
                            </tr>
                        <?php } ?>
                            <tr><td colspan="2"><label>Mesaj:</label></td></tr>
                            <tr><td colspan="2"><textarea name="mesaj" cols="70" rows="8"></textarea></td></tr>
                            <tr><td colspan="2"><input type="submit" value="Trimite mesajul" /></td></tr>
                    </tbody>
                </table>
            </form>
		</main>
	</body>
</html>
