<?php

session_start();
require_once "../_inc/setup.inc.php";
require_once "../_inc/topper.inc.php";
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_clase/Carte.class.php";

if (isset($_GET["id-carte"]) && !empty(trim($_GET["id-carte"])) && is_numeric($_GET["id-carte"])) {
	$id_carte = $_GET["id-carte"];
}

if (isset($_SESSION["id_utilizator"])) {
	$id_utilizator = $_SESSION["id_utilizator"];

	if (isset($_POST["nota"])) {
		$nota = intval($_POST["nota"]);
		$interog = $bd->prepare("SELECT * FROM note WHERE id_utilizator=? AND id_carte=?");
		$interog->bind_param("ii", $id_utilizator, $id_carte);
		$interog->execute();
		$rez = $interog->get_result();

		if ($rez->num_rows == 0) {
			if ($nota > 0) {
				$interog = $bd->prepare("INSERT INTO note (id_utilizator, id_carte, valoare_nota) VALUES (?, ?, ?)");
				$interog->bind_param("iii", $id_utilizator, $id_carte, $nota);
				$de_executat = true;
			}
		}
		else {
			if ($nota > 0) {
				$interog = $bd->prepare("UPDATE note SET valoare_nota=? WHERE id_utilizator=? AND id_carte=?");
				$interog->bind_param("iii", $nota, $id_utilizator, $id_carte);
				$de_executat = true;
			}
			else {
				$interog = $bd->prepare("DELETE FROM note WHERE id_utilizator=? AND id_carte=?");
				$interog->bind_param("ii", $id_utilizator, $id_carte);
				$de_executat = true;
			}
		}

		if (isset($de_executat) && $de_executat) {
			$interog->execute();
		}

		$notat = true;
	}
}

?>

		<title>Cărți • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			<div id="div-carte">
				<?php if (!isset($id_carte))  { ?>
					<p class="err-text">Nu a fost specificat un id valid al unei cărți.</p>
				<?php } else {

					if (isset($notat) && $notat) { ?>
						<p class="succes-text">Nota ta a fost modificată cu succes!</p>
					<?php }

					$interog = $bd->prepare("SELECT * FROM carti WHERE id_carte=?");
					$interog->bind_param("i", $id_carte);
					$interog->execute();
					$rez = $interog->get_result();
					
					if ($rez->num_rows == 0) { ?>
						<p class="err-text">Nu a fost găsită o carte cu id-ul specificat.</p>
					<?php } else {
						
						$linie = $rez->fetch_assoc();
						$carte = new Carte($linie);
					
						?>

						<h2><?php echo $carte->titlu; ?></h2>
						<p>Autor(i): <?php echo $carte->get_str_autori($bd); ?></p>
						<p>Exemplare disponibile: <?php echo $carte->numar_disponibile; ?></p>

						<!-- nota -->
						<?php if (isset($id_utilizator)) {
							if (!isset($nota)) {
								$interog = $bd->prepare("SELECT * FROM note WHERE id_utilizator=? AND id_carte=?");
								$interog->bind_param("ii", $id_utilizator, $id_carte);
								$interog->execute();
								$rez = $interog->get_result();
								if ($rez->num_rows == 0)
									$nota = -1;
								else
									$nota = $rez->fetch_assoc()["valoare_nota"];
							} ?>
							<form action="" method="POST">
								<label for="nota">Nota ta: </label>
								<select name="nota">
									<option <?php if ($nota <= 0) echo "selected"; ?> selected value="-1">Nicio notă</option>
									<?php for ($i = 1; $i <= 5; $i++) { ?>
										<option <?php if ($nota == $i) echo "selected class=\"optiune-salvata\""; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
									<?php } ?>
								</select>
								<input type="submit" value="Salvează"/>
							</form>
						<?php }
					}

				} ?>
			</div>
		</main>
	</body>
</html>
