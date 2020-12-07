<?php

session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "admin")
    header("Location: ../index.php?err=interzis");
else {

require_once "../_inc/setup.inc.php";
require "../_inc/topper.inc.php";
require "../_inc/conexiune_bd.inc.php";

if (isset($_POST["rol"]) && isset($_GET["id-modif"])) {
    $rol_nou = $_POST["rol"];
    $id_util_modif = $_GET["id-modif"];
    $rez = $bd->query("UPDATE utilizatori SET rol='$rol_nou' WHERE id_utilizator='$id_util_modif'");
    if ($bd->affected_rows == 0)
        $nu_exista = true;
    else
        $rol_modificat = true;
}

?>

		<link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>_css/administreaza-conturi.css" />
		<title>Cărți • Templul Cărților</title>
	</head>
	<body>
		<?php require "../_inc/header.inc.php"; ?>
		<main>
            <?php if (isset($nu_exista) && $nu_exista && isset($_POST["rol"]) && isset($_GET["id-modif"])) { ?>
                <p class="err-text">Un id de utilizator gresit a fost introdus</p>
            <?php }
            
            $rez = $bd->query("SELECT * FROM utilizatori ORDER BY email");
            while ($utilizator = $rez->fetch_assoc()) { ?>
                <table class="div-cont">
                    <tbody>
                        <tr>
                            <td>Email: </td>
                            <td class="email-cont"><?php echo $utilizator["email"]; ?></td>
                        </tr>
                        <tr>
                            <td>Prenume și nume: </td>
                            <td><?php echo $utilizator["prenume"]." ".$utilizator["nume"]; ?></td>
                        </tr>
                        <tr>
                            <?php if ($utilizator["rol"] == "nevalidat") { ?>
                                <td colspan="2">Cont nevalidat</td>
                            <?php } else { ?>
                                    <form action="?id-modif=<?php echo $utilizator["id_utilizator"]; ?>" method="POST">
                                        <td><label for="rol">Rol: </label></td>
                                        <td>
                                            <select name="rol">
                                                <?php foreach (["simplu", "bibliotecar", "admin"] as $rol) { ?>
                                                    <option <?php if ($utilizator["rol"] == $rol) echo "selected class=\"optiune-salvata\""; ?> value="<?php echo $rol; ?>"><?php echo $rol; ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="submit" value="Modifică" />
                                        </td>
                                    </form>
                        </tr>
                        <?php }
                        
                        if (isset($_GET["id-modif"]) && $_GET["id-modif"] == $utilizator["id_utilizator"] && isset($rol_modificat) && $rol_modificat) { ?>
                            <p class="succes-text" style="text-align: left;">Rolul a fost modificat cu succes!</p>
                        <?php }

                        ?>
                    </tbody>
                </table>
            <?php }

            ?>
		</main>
	</body>
</html>

<?php } ?>
