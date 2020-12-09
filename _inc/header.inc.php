    <header>
		<div id="top-div">
			<a href="<?php echo ROOT; ?>">Templul Cărților</a>
		</div>

		<nav id="navbar">
			<ul class="meniu">
				<li><a href="<?php echo ROOT; ?>">Acasă</a></li>
				<li><a href="<?php echo ROOT; ?>prezentare">Prezentare</a></li>
				<li>
					<a href="<?php echo ROOT; ?>carti">Explorează cărți</a>
					<ul class="submeniu">
						<li><a href="<?php echo ROOT; ?>carti?sort=recente">Cele mai recente</a></li>
						<li><a href="<?php echo ROOT; ?>carti?sort=imprumutate">Cele mai împrumutate</a></li>
					</ul>
				</li>
				<li>
					<?php if (isset($_SESSION["id_utilizator"])) { ?>

						<div><?php echo $_SESSION["prenume"]." ".$_SESSION["nume"]; ?></div>
						<ul class="submeniu">
							<li><a href="<?php echo ROOT; ?>profil/setari">Setări</a></li>
							<li>
								<form action="<?php echo ROOT; ?>_inc/logout.inc.php" method="GET">
									<input type="submit" value="Logout" />
								</form>
							</li>
						</ul>

					<?php } else { ?>

						<div>Intră în cont</div>
						<ul class="submeniu">
							<li><a href="<?php echo ROOT; ?>autentificare">Autentificare</a></li>
							<li><a href="<?php echo ROOT; ?>inregistrare">Înregistrare</a></li>
						</ul>

					<?php } ?>
				</li>

				<?php if (isset($_SESSION["rol"]) && in_array($_SESSION["rol"], ["bibliotecar", "admin"])) { ?>

					<li>
						<div>Acțiuni</div>
						<ul class="submeniu">
							<?php if ($_SESSION["rol"] == "admin") { ?>
								<li><a href="<?php echo ROOT; ?>actiuni/adauga-carte">Adaugă o carte</a></li>
								<li><a href="<?php echo ROOT; ?>actiuni/administreaza-conturi">Administrează conturile</a></li>
							<?php } ?>
							<li><a href="<?php echo ROOT; ?>actiuni/creaza-abonament">Crează un abonament</a></li>
							<li><a href="<?php echo ROOT; ?>actiuni/gestioneaza-imprumuturi">Gestionează împrumuturile</a></li>
						</ul>
				</li>

				<?php } ?>
				
				<li><a href="<?php echo ROOT; ?>contact">Contact</a></li>
			</ul>
		</nav>

		<div id="searchbar">
			<form method="GET" action="<?php echo ROOT; ?>carti" autocomplete="off">
				<input type="text" name="caut" placeholder="Caută cărți sau autori" />
			</form>
		</div>
	</header>
