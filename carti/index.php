<?php

session_start();
require_once "../_inc/setup.inc.php";
require "../_inc/topper.inc.php";
require "../_inc/conexiune_bd.inc.php";

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
							// else if ($sort_crit == "imprumutate")
						}
						$interog_carte = $bd->prepare($str_interog_carte);
						// $interog_carte->bind_param()
						$interog_carte->execute();
						$rez_carte = $interog_carte->get_result();
						while ($linie_carte = $rez_carte->fetch_assoc()) {
							$id_carte = $linie_carte["id_carte"];
							$titlu = $linie_carte["titlu"];
							
							$interog_autori = $bd->prepare("SELECT nume_autor FROM autori_carti WHERE id_carte=?");
							$interog_autori->bind_param("i", $id_carte);
							$interog_autori->execute();
							$rez_autori = $interog_autori->get_result();
							$autori = [];
							while ($linie_autori = $rez_autori->fetch_assoc()) {
								array_push($autori, $linie_autori["nume_autor"]);
							}
							$autori = implode(", ", $autori);

							echo "
								<div class=\"linie-carte\">
									<div>
										<a href=\"carte.php?id=".$id_carte."\">".$titlu."</a>
										<p>Autor(i): ".$autori."</p>
									</div>
								</div>
							";
						}

						?>
					</ul>
				</div>
			</div>
		</main>
	</body>
</html>
