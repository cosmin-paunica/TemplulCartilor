<?php

session_start();
require_once "../_inc/setup.inc.php";
require_once "../_inc/topper.inc.php";
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_clase/Carte.class.php";

?>

		<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/carte.css" />
		<title><?php echo $carte_gasita ? $carte->titlu : "Carte"; ?> • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			
		</main>
	</body>
</html>
