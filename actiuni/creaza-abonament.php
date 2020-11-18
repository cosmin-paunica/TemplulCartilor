<?php

session_start();
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ["bibliotecar", "admin"]))
    header("Location: ../index.php");
header("Content-Type: text/html; charset=utf-8");

require_once "../_inc/setup.inc.php";
require "../_inc/conexiune_bd.inc.php";
require_once "../_inc/topper.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="../_css/forme.css" />
		<title>Crează un abonament • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
            <?php

            if (isset($_GET["err"])) {
                $err = $_GET["err"];
                if ($err == "modificat")
                    echo "<p class=err-text>Formularul a fost modificat înainte de submisie.</p>";
                else if ($err == "incomplet")
                    echo "<p class=err-text>Unul dintre câmpurile obligatorii nu a fost completat.</p>";
                else if ($err == "nuexista")
                    echo "<p class=err-text>Nu a fost găsit niciun utilizator cu adresa de email introdusă.</p>";
            }

            if (isset($_GET["msg"])) {
                $msg = $_GET["msg"];
                if ($msg == "creat")
                    echo '<p class="succes-text">Abonamentul a fost adăugat cu succes!</p>';
            }

            ?>
			<form action="../_inc/creaza-abonament.inc.php" method="POST" accept-charset="UTF-8">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <td><label for="email-client">Adresa de email a clientului:<span class="stea-camp-obligatoriu">*</span></label></td>
                            <td><input type="text" name="email-client" />
                        </tr>
                        <tr>
                            <td><label for="data-inceput">Data de început:<span class="stea-camp-obligatoriu">*</span></label></td>
                            <td><input type="date" name="data-inceput" />
                        </tr>
                        <tr>
                            <td><label for="durata">Durată:<span class="stea-camp-obligatoriu">*</span></label></td>
                            <td>
                                <select name="durata">
                                    <option selected value="">Alege...</option>
                                    <option value="1-luna">1 lună</option>
                                    <option value="6-luni">6 luni</option>
                                    <option value="2-ani">2 ani</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" value="Crează abonamentul" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
		</main>
	</body>
</html>
