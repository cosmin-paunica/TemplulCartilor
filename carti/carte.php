<?php

session_start();
require_once "../_inc/setup.inc.php";
require_once "../_inc/topper.inc.php";

if (isset($_GET["cautare"]))
    $_GET["cautare"] = strtolower($_GET["cautare"]);

?>

		<title>Cărți • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			
		</main>
	</body>
</html>
