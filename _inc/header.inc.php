    <header>
		<div id="top-div">
			<a href="<?php echo ROOT; ?>">Templul Cărților</a>
		</div>

		<nav id="navbar">
			<ul class="meniu">
				<li><a href="<?php echo ROOT; ?>">Acasă</a></li>
				<li><a href="<?php echo ROOT; ?>prezentare.php">Prezentare</a></li>
				<li>
					<a href="<?php echo ROOT; ?>carti">Explorează cărți</a>
					<ul class="submeniu">
						<li><a href="<?php echo ROOT; ?>carti?sort=recente">Cele mai recente</a></li>
						<li><a href="<?php echo ROOT; ?>carti?sort=imprumutate">Cele mai împrumutate</a></li>
					</ul>
				</li>
				<li>
					<?php if (isset($_SESSION["id_utilizator"])) { ?>

						<a href="<?php echo ROOT; ?>profil"><?php echo $_SESSION["prenume"]." ".$_SESSION["nume"]; ?></a>
						<ul class="submeniu">
							<?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") { ?>
								<li><a href="<?php echo ROOT; ?>carti/adauga-carte.php">Adaugă carte</a></li>
							<?php } ?>
							<li>
								<form action="<?php echo ROOT; ?>_inc/logout.inc.php" method="GET">
									<input type="submit" value="Logout" />
								</form>
							</li>
						</ul>

					<?php } else { ?>

						<div>Intră în cont</div>
						<ul class="submeniu">
							<li><a href="<?php echo ROOT; ?>autentificare.php">Autentificare</a></li>
							<li><a href="<?php echo ROOT; ?>inregistrare.php">Înregistrare</a></li>
						</ul>

					<?php } ?>
				</li>
			</ul>
		</nav>

		<div id="searchbar">
			<form method="GET" action="<?php echo ROOT; ?>carti" autocomplete="off">
				<input type="text" name="caut" placeholder="Caută cărți sau autori" />
			</form>
		</div>
	</header>
