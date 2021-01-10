<?php

session_start();
require_once "_inc/setup.inc.php";
require_once "_inc/topper.inc.php";

?>

		<title>Prezentare • Templul Cărților</title>
	</head>
	<body>
        <?php require "_inc/header.inc.php"; ?>
		<main>
			<h1>Prezentare</h1>

			<section>
				<h2>Prezentare generală</h2>
				<p>Acest website va avea rolul de a ușura interacțiunea clienților cu o bibliotecă. Site-ul nu se va preocupa cu gestionarea activităților interne ale bibliotecii, precum angajați, salarii etc.</p>
				<p>Pe acest site, vor exista conturi de tip admin, bibliotecar, client și utilizator simplu. Oricine își poate face cont de utilizator simplu. Bibliotecarii pot transforma un cont de utilizator simplu în cont de client (bibliotecarul crează un abonament pentru persoana ce deține contul), iar adminii vor putea transforma un cont de utilizator simplu sau de client în cont de bibliotecar (când bibliotecarul sau adminul este angajat) sau de admin.</p>
			</section>

			<section>
				<h2>Tipuri de utilizatori</h2>
				<ul>
					<li>
						<section>
							<h3>Utilizatori simpli</h3>
							<p>Utilizatorii simpli vor putea:</p>
							<ul>
								<li class="task-completat">Să vadă ce cărți aparțin bibliotecii.</li>
								<li class="task-completat">Să caute o carte sau un autor în bibliotecă, folosind cuvinte cheie.</li>
								<li class="task-completat">Să vadă câte exemplare ale unei cărți sunt disponibile în bibliotecă.</li>
								<li class="task-completat">În cazul cărților pentru care toate exemplarele sunt împrumutate, să vadă când este data de predare a unui exemplar (acest lucru nu va fi foarte precis, întrucât clienții își pot prelungi împrumuturile sau pot aduce cărțile înapoi mai devreme).</li>
							</ul>
						</section>
					</li>
					<li>
						<section>
							<h3>Clienți</h3>
							<p>Pe lângă acțiunile specifice unui utilizator simplu, clienții vor putea:</p>
							<ul>
								<li class="task-completat">Să vadă cărțile care sunt în posesia lor la un moment dat și să vadă termenele de predare ale acestora.</li>
								<li>Să scrie recenzii ale cărților împrumutate, după ce le returnează.</li>
							</ul>
						</section>
					</li>
					<li>
						<section>
							<h3>Bibliotecari</h3>
							<p>Pe lângă activitățile specifice clienților, bibliotecarii vor putea:</p>
							<ul>
								<li class="task-completat">Să marcheze un cont de utilizator simplu ca fiind cont de client.</li>
								<li class="task-completat">Să înregistreze împrumutul unei cărți unui client, lucru ce va schimba informații de pe site, precum numărul de exemplare ale cărții disponibile în biblitoecă.</li>
							</ul>
						</section>
					</li>
					<li>
						<section>
							<h3>Admini</h3>
							<p>Pe lângă activitățile specifice bibliotecarilor, adminii vor putea:</p>
							<ul>
								<li class="task-completat">Să marcheze un cont ca fiind cont de bibliotecar sau de admin.</li>
								<li class="task-completat">Să adauge o nouă carte sau să modifice stocul unei cărți din bibliotecă.</li>
							</ul>
						</section>
					</li>
				</ul>
			</section>

			<section>
				<h2>Baza de date</h2>
				<p>Câteva dintre tabelele bazei de date vor fi:</p>
				<ul>
					<li>useri</li>
					<li>carti</li>
					<li>imprumuturi</li>
					<li>rezervari</li>
					<li>exemplare</li>
				</ul>
			</section>
		</main>
	</body>
</html>
