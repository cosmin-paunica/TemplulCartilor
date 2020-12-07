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
				// nota
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

				// imprumut
				if (isset($_POST["email-imprumut"])) {
					$email = $_POST["email-imprumut"];
					if (!filter_var($email, FILTER_VALIDATE_EMAIL))
						$err = "email-invalid";
					else {
						$interog = $bd->prepare("SELECT id_utilizator FROM utilizatori WHERE email=?");
						$interog->bind_param("s", $email);
						$interog->execute();
						$rez = $interog->get_result();
						if ($rez->num_rows == 0)
							$err = "inexistent";
						else {
							// select imprumuturile utilizatorului pe aceasta carte care nu au fost predate
							$rez = $bd->query("
								SELECT *
								FROM imprumuturi
								WHERE id_utilizator='$id_utilizator'
								AND id_carte='$id_carte'
								AND predat=0
							");
							if ($rez->num_rows != 0) {
								$imprumut = $rez->fetch_assoc();
								$termen_predare = $imprumut["termen_predare"];
								if ($termen_predare < date("YYYY-mm-dd")) {
									$err = "deja-imp";
								}
								else {
									$err = "intarziat";
								}
							}
							else {
								$rez = $bd->query("
									INSERT INTO imprumuturi (id_utilizator, id_carte, data_inceput, termen_predare)
									VALUES ('$id_utilizator', '$id_carte', DATE(NOW()), DATE_ADD(DATE(NOW()), INTERVAL 2 WEEK))
								");
								$rez = $bd->query("
									UPDATE carti
									SET numar_disponibile = numar_disponibile - 1
									WHERE id_carte='$id_carte'
								");
								$carte->numar_disponibile -= 1;
								$msg = "imp-reusit";
							}
						}
					}
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
					<p class="succes-text">Nota ta a fost salvată cu succes!</p>
				<?php }
				if (isset($msg)) {
					if ($msg == "imp-reusit") { ?>
						<p class="succes-text">Împrumutul a fost înregistrat cu succes! Termen de predare: <?php echo date("d.m.Y", strtotime("+2 weeks")); ?></p>
					<?php }
				}

				if (isset($err)) {
					if ($err == "email-invalid") { ?>
						<p class="err-text">Nu ați introdus un email valid!</p>
					<?php } else if ($err == "inexistent") { ?>
						<p class="err-text">Nu există vreun cont cu email-ul introdus.</p>
					<?php } else if ($err == "deja-imp") { ?>
						<p class="err-text">Utilizatorul deja are un împrumut pe această carte.</p>
					<?php } else if ($err == "intarziat") { ?>
						<p class="err-text">Utilizatorul deja are un împrumut pe această carte și a întârziat predarea.</p>
					<?php }
				} ?>
				<div class="div-carte">
					<div class="div-img-carte">
						<img src="../_img/<?php echo $carte->fisier_imagine; ?>" />
					</div>

					<div class="div-continut-carte">
						<h1><?php echo $carte->titlu; ?></h1>
						<p>Autor(i): <?php echo $carte->get_str_autori($bd); ?></p>
						<?php if ($carte->numar_disponibile > 0) { ?>
							<p>Exemplare disponibile: <?php echo $carte->numar_disponibile; ?></p>
						<?php } else {
							$interog = $bd->prepare("
								SELECT MIN(termen_predare) termen_min
								FROM imprumuturi
								WHERE id_carte=?
								AND predat=0
							");
							$interog->bind_param("i", $carte->id_carte);
							$interog->execute();
							$rez = $interog->get_result();
							$linie = $rez->fetch_assoc();
							$termen_min = $linie["termen_min"];
							?> <p>Toate exemplarele acestei cărți sunt împrumutate clienților noștri. Recomandăm să verifici din nou în data de <?php echo date("d.m.Y", strtotime($termen_min." +1 day")) ?></p> <?php
						} ?>

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

							<?php 
							
							
							if (isset($_SESSION["rol"]) && in_array($_SESSION["rol"], ["bibliotecar", "admin"])) { ?>
								<br>
								<h3>Oferă cartea spre împrumut:</h3>
								<form action="" method="POST">
									<label>Email-ul utilizatorului: </label>
									<input type="email" name="email-imprumut" />
									<input type="submit" value="Împrumută" />
								</form>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</main>
	</body>
</html>
