<?php

session_start();
require_once "../_setup/setup.php";
require_once "_inc/topper.inc.php";
require "_setup/conexiune_bd.php";

?>

		<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/carti.css" />
		<title>Cărți • Templul Cărților</title>
	</head>
	<body>
		<?php require "_inc/header.inc.php"; ?>
		<main>
			<?php if (isset($_GET["caut"]) && !empty($_GET["caut"])) { ?>
				<p>Vezi rezultatele căutării pentru <?php echo $_GET["caut"]; ?>.</p>
			<?php } else { ?>
				<p>Vezi cărțile bibliotecii.</p>
			<?php } ?>
			<div class="coloane">
				<div class="coloana-sort-filter">
					<form method="GET" action="">
						
					</form>
				</div>

				<div class="coloana-carti">
					<ul>
						<?php

						$interog_carte = $bd->prepare("SELECT * FROM carti");
						// $interog_carte->bind_param()
						$interog_carte->execute();
						$rez_carte = $interog_carte->get_result();
						while ($linie_carte = $rez_carte->fetch_assoc()) {
							$id_carte = $linie_carte["id_carte"];
							$titlu = $linie_carte["titlu"];
							$fisier_img = $linie_carte["fisier_imagine"];
							
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
										<a href=\"carte.php?id=".$id_carte."\">
											<img src=\"../_img/".$fisier_img."\" height=\"100\" />
										</a>
									</div>
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
