<?php

session_start();
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ["bibliotecar", "admin"]))
    header("Location: ../index.php?err=interzis");
header("Content-Type: text/html; charset=utf-8");

require_once "../_inc/setup.inc.php";
require "../_inc/conexiune_bd.inc.php";
require_once "../_inc/topper.inc.php";

?>

        <link rel="stylesheet" type="text/css" href="../_css/gestioneaza-imprumuturi.css" />
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
                        $id_utilizator = $rez->fetch_assoc()["id_utilizator"];
                        $rez = $bd->query("
                            SELECT i.*, c.titlu
                            FROM imprumuturi i JOIN carti c ON (i.id_carte = c.id_carte)
                            WHERE id_utilizator='$id_utilizator'
                            AND predat=0;
                        ");
                        
                        ?> <p>Împrumuturile utilizatorului cu email-ul <?php echo $email ?>:</p> <?php
                        while ($imprumut = $rez->fetch_assoc()) { ?>
                            <table class="tabel-imprumut">
                                <tbody>
                                    <tr>
                                        <td>Titlul cărții: </td>
                                        <td><?php echo $imprumut["titlu"]; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Termen predare: </td>
                                        <td>
                                            <?php 
                                            echo date("d.m.Y", strtotime($imprumut["termen_predare"]));
                                            if (strtotime($imprumut["termen_predare"]) < strtotime("now"))
                                                echo "<span style=\"color: red;\"> Întârziat!</span>"
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><a href="../_inc/incheie-imprumut.php?id_utilizator=<?php echo $id_utilizator; ?>&id_carte=<?php echo $imprumut["id_carte"]; ?>">Încheie împrumutul</a></td>
                                    </tr>
                                </tbody>
                            </table>
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
