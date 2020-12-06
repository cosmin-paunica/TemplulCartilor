<?php

session_start();
require_once "../_inc/setup.inc.php";
require_once "../_inc/topper.inc.php";
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_clase/Carte.class.php";

if (isset($_GET["id-carte"]) && !empty(trim($_GET["id-carte"])) && is_numeric($_GET["id-carte"])) {
	$id_carte_valid = true;
	$id_carte = $_GET["id-carte"];
	$carte_gasita = true;
	try {
		$carte = Carte::din_bd($bd, $id_carte);
		if (isset($_SESSION["id_utilizator"])) {
			$id_utilizator = $_SESSION["id_utilizator"];
			
			if ($carte_gasita) {
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
		}
	} catch (Exception $e) { 
		$carte_gasita = false;
	}
}
else {
	$id_carte_valid = false;
}

?>

		<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/carte.css" />
		<title><?php echo $carte_gasita ? $carte->titlu : "Carte"; ?> • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			<?php if (!$id_carte_valid) { ?>
				<p class="err-text">Nu a fost introdus un ID valid pentru o carte.</p>
			<?php } else if (!$carte_gasita) { ?>
				<p class="err-text">Nu a fost găsită o carte cu ID-ul specificat.</p>
			<?php } else {
				if (isset($notat) && $notat) { ?>
					<p class="succes-text">Nota ta a fost modificată cu succes!</p>
				<?php } ?>
				<div class="div-carte">
					<div class="div-img-carte">
						<img src="../_img/<?php echo $carte->fisier_imagine; ?>" />
					</div>

					<div class="div-continut-carte">
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
						</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</main>
	</body>
</html>
