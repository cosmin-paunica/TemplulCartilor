<?php

session_start();

if (!isset($_SESSION["id_utilizator"]))
    header("Location: ../index");
else {
    $id_utilizator = $_SESSION["id_utilizator"];

require_once "../_inc/setup.inc.php";
require_once "../_inc/topper.inc.php";
require_once "../_inc/conexiune_bd.inc.php";
require_once "../_clase/Carte.class.php";
require_once "../_functii/functii.php";

?>

		<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/imprumuturi.css" />
		<title>Împrumuturile tale • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
            <h1>Împrumuturile tale active</h1>
            
            <a href="../_inc/imprumuturi-excel">Descarcă istoricul tuturor împrumuturilor tale în format Excel</a>

            <?php

            $rez = $bd->query("
                SELECT *
                FROM imprumuturi
                WHERE id_utilizator=$id_utilizator
                AND data_predare IS NULL
                ORDER BY termen_predare
            ");
            while ($imp = $rez->fetch_object()) {
                $carte = Carte::din_bd($bd, $imp->id_carte); ?>
                <div class="div-imprumut">
                    <p><?= $carte->titlu ?>, autor(i): <?= $carte->get_str_autori() ?> </p>
                    <p>Data de început: <?= $imp->data_inceput ?></p>
                    <p>Termen de predare: <?= $imp->termen_predare ?></p>
                    <?php if (strtotime($imp->termen_predare) < strtotime("now")) { ?> <p style="color: red; font-weight: bold">Termenul de predare a expirat acum <?= zile_diferenta(date("now"), $imp->termen_predare) ?> zile!</p> <?php } ?>
                </div>
            <?php } ?>
		</main>
	</body>
</html>

<?php } ?>
