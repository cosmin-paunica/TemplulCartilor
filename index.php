<?php

session_start();
// session_destroy();
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
					echo '<p class="succes-text">Contul a fost creat cu succes!</p>';
				else if ($msg == "contsters")
					echo '<p class="succes-text">Contul a fost șters cu succes!</p>';
			}

			?>
			<p>Bine ați venit!</p>
		</main>
	</body>
</html>
