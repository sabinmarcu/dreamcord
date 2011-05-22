<div class="admin">
	<div class="label"><?php echo (isset($_SESSION['username']) ? "<li id='username'>Bine ai revenit, ".getUserName(1)."</li>" : ""); getAdmin(); ?></div>
</div>
<div class="colors">
	<?php $colors = explode(",", str_replace(" ", "", Oski::app() -> theme -> themeExtras['colors'])); $names = explode(",", str_replace(" ", "", Oski::app() -> theme -> themeExtras['color_names'])); foreach($colors as $id => $color) : ?>
	<li class='<?php echo $color ?>'><a href="?color_scheme=<?php echo $color ?>&set_permanent=1"><div class="text"><?php echo$names[$id]?></div><div class='color'></div></a></li>
	<?php endforeach; ?>
</div>
<div class="themes">
	<li><img src="/oski_content/themes/cnng/images/dynamic.png" alt=""><a href="#?theme=cnng&set_permanent=1">Tema Dinamica</a></li>
</div>
<div class="switch">
	<div class="text">
		<?php if (Oski::app() -> getActp('module') == "") : ?> 	Intoarce-te la Pagina
		<?php else : ?> Deschide panoul de informatii
		<?php endif; ?>
	</div>
</div>

<?php if (Oski::app() -> getActp('module') == "") : ?>
	<header class="home">
		<a href="/"><div class='logowrapper'><div class="site logo"><div class="logoover"></div></div></div></a>
		<a href="/"><div class="text">
			<h1 class="site title"><?php getSiteTitle() ?></h1>
			<h2 class="site tagline"><?php getSiteTagline() ?></h2>
		</div></a>
		<nav><?php getNavigation() ?></nav>
	</header>
<?php  else :?>
	<header>
		<a href="/"><h1 class="site title"><?php getSiteTitle() ?></h1></a>
		<nav><?php getNavigation() ?></nav>
	</header>
<?php endif; ?>
<div class="container">
<section id='dashboard' class='<?php echo (Oski::app() -> getActp('module') == "" ? "visible" : "hidden") ?>'>
	<aside>
		<?php $nav = new nav_layout("secnav"); $nav -> getNav(); ?>
	</aside>
	<div class="content">
		<?php if(Oski::app() -> getActp('module') == "") : ?>
			<article>
				<h1 id="informatii_despre_site">Informatii despre site</h1>
				<p>Orice pagina este construita din 2 sectiuni : </p>
				<ol>
					<li>Pagina Propriu - Zisa
					</li>

					<li>Panou de Informatii :
						<ul>
							<li>Informatii actualizate zilnic</li>
							<li>Meniul de navigatie</li>
							<li>Informatii sumare</li>
						</ul>
					</li>
				</ol>
				<p>Pentru a accesa ori pagina propriu-zisa, ori panoul de informatii, apasati pe butonul din stanga-sus a paginii.</p>
				<h2>Scheme de culori</h2>
				<p>Tema dinamica (curenta) suporta mai multe scheme de culori (albastru, verde, galben, etc). Pentru a schimba schema de culori apasati pe culoarea respectiva din meniul din stanga-sus a paginii</p>
				<h2>Teme Specifice</h2>
				<p>Exista 3 teme distincte pentru acest site, fiecare cu scopul sau.</p>
				<ol>
					<li><h5>Tema Clasica</h5><p>Este o tema rudimentara, construita pentru persoanele ce nu pot utiliza celelalte 2 teme</p></li>
					<li><h5>Tema Dinamica</h5><p>Scopul acestei teme este de a crea o experienta dinamica interactiva pentru vizitatori. Este construita in asa fel incat toate informatiile sa fie usor accesibile, lasand in acelasi timp loc suficient pentru continut.</p></li>
					<li><h5>Tema de Citit</h5><p>Este o tema minimalista ce interzice prezenta oricaror interferente. Scopul ei este de a facilita citirea unei pagini in detrimentul efectelor grafice.</p></li>
				</ol>
				<h4>Deocamdata, din pacate, doar tema Dinamica este activa. In scurt timp vom reveni cu temele lipsa.</h4>
			</article>

		<?php endif ?>
		<?php $dashboard = new main_layout('dashboard'); $dashboard -> openModules(); $dashboard -> getViews(); ?>
	</div>
</section>
<section id='main' class='<?php echo (Oski::app() -> getActp('module') != "" ? "visible" : "hidden") ?>'>
<div class="content">