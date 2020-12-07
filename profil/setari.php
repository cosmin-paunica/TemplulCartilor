<?php

session_start();
if (!isset($_SESSION["id_utilizator"]))
	header("Location: ../");
	
require_once "../_inc/setup.inc.php";
require "../_inc/topper.inc.php";
require "../_inc/conexiune_bd.inc.php";

?>

		<title><?php echo $_SESSION["prenume"]." ".$_SESSION["nume"]; ?> • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			<p><a href="schimba-parola">Schimbă parola contului tău</a></p>
			<p><a href="sterge-contul">Șterge-ți contul</a></p>
		</main>
	</body>
</html>
