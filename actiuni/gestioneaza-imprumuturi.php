<?php

session_start();
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ["bibliotecar", "admin"]))
    header("Location: ../index.php?err=interzis");
header("Content-Type: text/html; charset=utf-8");

require_once "../_inc/setup.inc.php";
require "../_inc/conexiune_bd.inc.php";
require_once "../_inc/topper.inc.php";
require_once "../_clase/Carte.class.php";
require_once "../_functii/functii.php";

?>

        <link rel="stylesheet" type="text/css" href="../_css/imprumuturi.css" />
		<title>Gestionează împrumuturile • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
            <?php

            if (isset($_GET["err"])) {
                $err = $_GET["err"];
                if ($err == "invalid") { ?>
                    <p class="err-text">Id-ul utilizatorului sau cel al cărții este invalid!</p>
                <?php } else if ($err == "inexistent") { ?>
                    <p class="err-text">Nu există un împrumut neîncheiat pentru id-urile selectate!</p>
                <?php }
            }

            if (isset($_GET["msg"])) {
                $msg = $_GET["msg"];
                if ($msg == "incheiat") { ?>
                    <p class="succes-text">Împrumutul a fost încheiat cu succes!</p>
                <?php }
            }

            ?>

            <form action="" method="POST">
                <label>Email-ul utiliztorului: </label>
                <input type="email" name="email"/>
                <input type="submit" value="Vezi împrumuturile">
            </form>
            <?php

            if (isset($_POST["email"])) {
                $email = $_POST["email"];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    $err = "invalid";
                else {
                    $interog = $bd->prepare("
                        SELECT id_utilizator
                        FROM utilizatori
                        WHERE email=?
                    ");
                    $interog->bind_param("s", $email);
                    $interog->execute();
                    $rez = $interog->get_result();
                    if ($rez->num_rows == 0)
                        $err = "inexistent";
                    else {
                        $id_utilizator = $rez->fetch_object()->id_utilizator;
                        $rez = $bd->query("
                            SELECT *
                            FROM imprumuturi i
                            WHERE id_utilizator='$id_utilizator'
                            AND data_predare IS NULL;
                        ");
                        
                        ?> <p>Împrumuturile utilizatorului cu email-ul <?php echo $email ?>:</p> <?php
                        while ($imp = $rez->fetch_object()) {
                            $carte = Carte::din_bd($bd, $imp->id_carte); ?>
                            <div class="div-imprumut">
                                <p><?= $carte->titlu; ?>, autor(i): <?= $carte->get_str_autori() ?></p>
                                <p>Termen predare: <?= date("d.m.Y", strtotime($imp->termen_predare)) ?></p>
                                <?php if (strtotime($imp->termen_predare) < strtotime("now")) { ?> <p style="color: red; font-weight: bold">Termenul de predare a expirat acum <?= zile_diferenta(date("now"), $imp->termen_predare) ?> zile!</p> <?php } ?>
                                <p><a href="../_inc/incheie-imprumut.php?id_utilizator=<?php echo $id_utilizator; ?>&id_carte=<?php echo $carte->id_carte; ?>">Încheie împrumutul</a></p>
                            </div>
                        <?php }
                    }
                }
            }

            if (isset($err)) {
                if ($err == "") { ?>
                    <p class="err-text">Nu ați introdus un email valid.</p>
                <?php }
            }

            ?>
		</main>
	</body>
</html>
