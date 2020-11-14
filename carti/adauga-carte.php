<?php

session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin")
    header("Location: ../index.php");
    header("Content-Type: text/html; charset=utf-8");

require_once "../_setup/setup.php";
require "../_setup/conexiune_bd.php";
require_once "../_inc/topper.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="../_css/forme.css" />
		<title>Templul Cărților</title>
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
                else if ($err == "extimg")
                    echo "<p class=err-text>Imaginea trebuie să fie de tip jpg sau jpeg.</p>";
                else if ($err == "imguplderr")
                    echo "<p class=err-text>A apărut o eroare la încărcarea imaginii.</p>";
                else if ($err == "imgmare")
                    echo "<p class=err-text>Imaginea este prea mare. Ea trebuie să fie mai mică de 5MB.</p>";
            }

            if (isset($_GET["msg"])) {
                $msg = $_GET["msg"];
                if ($msg == "adaugata")
                    echo '<p class="succes-text">Cartea a fost adăugată cu succes!</p>';
            }

            ?>
			<form action="../_inc/adauga-carte.inc.php" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <td><label for="titlu">Titlu:<span class="stea-camp-obligatoriu">*</span></label></td>
                            <td><input type="text" name="titlu" />
                        </tr>
                        <tr>
                            <td><label for="autori">Autor(i):<span class="stea-camp-obligatoriu">*</span></label></td>
                            <td><input type="text" name="autori" placeholder="Separați autorii prin virgule" />
                        </tr>
                        <tr>
                            <td><label for="limba">Limbă:<span class="stea-camp-obligatoriu">*</span></label></td>
                            <td>
                                <select name="limba">
                                    <option selected value="">Alege...</option>
                                    <?php

                                    $rez = $bd->query("SELECT nume_limba FROM limbi ORDER BY nume_limba");
                                    if ($rez->num_rows > 0) {
                                        while ($linie = $rez->fetch_assoc()) {
                                            echo "<p>".$linie["nume_limba"]."</p>";
                                            echo "<option value=".$linie["nume_limba"].">".ucfirst($linie["nume_limba"])."</option>";
                                        }
                                    }

                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="limba-noua">(Sau limbă nouă)</label></td>
                            <td><input type="text" name="limba-noua" /></td>
                        </tr>
                        <tr>
                            <td><label for="data-pub">Data publicării:</label></td>
                            <td><input type="date" name="data-pub" /></td>
                        </tr>
                        <tr>
                            <td><label for="nr-pag">Număr de pagini:</label></td>
                            <td><input type="number" name="nr-pag" /></td>
                        </tr>
                        <tr>
                            <td><label for="fisier-img">Imagine</label></td>
                            <td><input type="file" name="fisier-img" /></td>
                        </tr>
                        <tr>
                            <td><label for="serie">Serie:</label></td>
                            <td>
                                <select name="serie">
                                    <option selected value="">Alege...</option>
                                    <?php

                                    $rez = $bd->query("SELECT nume_serie FROM serii ORDER BY nume_serie");
                                    if ($rez->num_rows > 0) {
                                        while ($linie = $rez->fetch_assoc())
                                            echo "<option>".$linie["nume_serie"]."</option>";
                                    }

                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="serie-noua">(Sau serie nouă)</label></td>
                            <td><input type="text" name="serie-noua" /></td>
                        </tr>
                        <tr>
                            <td><label for="goodreads">Link Goodreads:</label></td>
                            <td><input type="url" name="goodreads" />
                        </tr>
                        <tr>
                            <td colspan="2"><input type="submit" value="Adaugă" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
		</main>
	</body>
</html>
