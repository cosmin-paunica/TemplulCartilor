<?php

session_start();
require_once "../_setup/setup.php";
require_once "_inc/topper.inc.php";

?>

		<title><?php echo $_SESSION["prenume"]." ".$_SESSION["nume"]; ?> • Templul Cărților</title>
	</head>
	<body>
		<?php require "_inc/header.inc.php"; ?>
		<main>
			<p>Vezi aici date despre profilul tău.</p>
		</main>
	</body>
</html>
