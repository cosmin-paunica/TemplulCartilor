<?php

session_start();
require_once "_inc/setup.inc.php";
require_once "_inc/topper.inc.php";

?>

		<title>Templul Cărților</title>
	</head>
	<body>
		<?php require "_inc/header.inc.php"; ?>
		<main>
			<?php

			if (isset($_GET["msg"])) {
				$msg = $_GET["msg"];
				if ($msg == "inregistrat")
					echo '<p class="succes-text">Contul a fost creat cu succes! Vă rugăm să vă verificați email-ul pentru a vă valida contul.</p>';
				else if ($msg == "contsters")
					echo '<p class="succes-text">Contul a fost șters cu succes!</p>';
			}

			if (isset($_GET["err"])) {
				$err = $_GET["err"];
				if ($err == "interzis") {
					?> <p class="err-text">Nu aveți permisiunea de a accesa pagina!</p> <?php
				} else if ($err == "gresit") {
					?> <p class="err-text">Au fost trimise informații greșite prin url!</p> <?php
				} else if ($err == "dejavalidat") {
					?> <p class="err-text">Contul este deja validat!</p> <?php
				}
			}

			?>
			<p>Bine ați venit!</p>
		</main>
	</body>
</html>
