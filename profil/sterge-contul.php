<?php

session_start();
if (!isset($_SESSION["id_utilizator"]))
    header("Location: ../");
    
require_once "../_inc/setup.inc.php";
require "../_inc/topper.inc.php";
require "../_inc/conexiune_bd.inc.php";

?>

		<title>Setările profilului • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
			<p>Ești sigur că dorești să îți ștergi contul?</p>
            <form action="../_inc/sterge-contul.inc.php" method="POST">
                <input type="submit" value="Da" />
            </form>
            <form action="../profil/setari" method="POST">
                <input type="submit" value="Nu" />
            </form>
		</main>
	</body>
</html>
