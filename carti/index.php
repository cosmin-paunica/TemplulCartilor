<?php

session_start();
require_once "../_inc/setup.inc.php";
require_once "../_inc/topper.inc.php";
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_clase/Carte.class.php";
require_once "../_inc/functii.inc.php";

?>

		<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/carti.css" />
		<title>Cărți • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			<div class="coloane">
				<div class="coloana-sort-filter">
					<form method="GET" action="">
						<p>Sortează după:</p>
						<input type="radio" name="sort" id="sort-recente" value="recente" <?php if (isset($_GET["sort"]) && $_GET["sort"] == "recente") echo "checked"; ?> />
						<label for="sort-recente">Cele mai recente</label><br />
						<input type="radio" name="sort" id="sort-imprumutate" value="imprumutate" <?php if (isset($_GET["sort"]) && $_GET["sort"] == "imprumutate") echo "checked"; ?> />
						<label for="sort-imprumutate">Cele mai împrumutate</label><br />
						<input type="submit" value="Aplică" />
					</form>
				</div>

				<div class="coloana-carti">
					<ul>
						<?php

						$str_interog_carte = "SELECT * FROM carti";
						if (isset($_GET["sort"])) {
							$sort_crit = $_GET["sort"];
							if ($sort_crit == "recente")
								$str_interog_carte .= " ORDER BY data_adaugare DESC";
							else if ($sort_crit == "imprumutate") {
								$str_interog_carte = "
									SELECT *, (
										SELECT COUNT(*)
										FROM imprumuturi
										WHERE id_carte = c.id_carte
									) nr_imprumuturi
									FROM carti c
									ORDER BY nr_imprumuturi DESC;
								";
							}
						}
						else if (isset($_GET["caut"]) && !empty($_GET["caut"])) {
							$caut = fara_caractere_speciale(addslashes($_GET["caut"]));
							$caut = explode("+", $caut);
							$str_interog_carte = "";
							foreach ($caut as $cuv) {
								$cuv = strtolower($cuv);
								$str_interog_carte .= "SELECT DISTINCT * FROM carti c JOIN autori_carti a ON (c.id_carte = a.id_carte) WHERE LOWER(titlu) LIKE '%$cuv%' OR LOWER(nume_autor) LIKE '%$cuv%' UNION";
							}
							$str_interog_carte = substr($str_interog_carte, 0, strlen($str_interog_carte) - strlen(" UNION"));
						}

						$interog_carte = $bd->prepare($str_interog_carte);
						// $interog_carte->bind_param()
						$interog_carte->execute();
						$rez_carte = $interog_carte->get_result();
						if ($rez_carte->num_rows == 0) { ?>
							<p>Nu a fost găsit niciun rezultat care să corespundă căutării.</p>
						<?php } else {
							$id_curent_carte = -1;
							while ($linie_carte = $rez_carte->fetch_object()) {
								if ($id_curent_carte != $linie_carte->id_carte) {	// pot aparea carti de mai multe ori cand se cauta o carte cu mai multi autori
									$id_curent_carte = $linie_carte->id_carte;
									$carte = new Carte($bd, $linie_carte); ?>
									<div class="linie-carte">
										<div class="div-img-linie">
											<a href="carte?id-carte=<?php echo $carte->id_carte; ?>"><img src="../_img/<?php echo $carte->fisier_imagine; ?>" /></a>
										</div>
										<div class="div-continut-linie">
											<a class="link-titlu" href="carte?id-carte=<?php echo $carte->id_carte; ?>"><?php echo $carte->titlu; ?></a>
											<p>Autor(i): <?php echo $carte->get_str_autori(); ?></p>
										</div>
									</div>
							<?php } 
							}
						}

						?>
					</ul>
				</div>
			</div>
		</main>
	</body>
</html>
