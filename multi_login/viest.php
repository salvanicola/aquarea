<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Aquarea</title>
	<meta name="title" content="Aquarea" />
	<meta name="description" content="" />
	<meta name="keywords" content="piscina, palestra, corsi nuoto, acquagym, agonismo, " />
	<meta name="language" content="italian it" />
	<meta name="author" content="Varo Manuel, Sgreva Alessandro, Salvadore Nicola, Motterle Michele" />
	<link rel="stylesheet" type="text/css" href="../css/mobilefirstlevel.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../css/firstlevel.css" media="screen and (min-device-width:600px)"/>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat"/> 
	<script type="text/javascript" src="../js/scriptsidebar.js"></script>
	<script type="text/javascript" src="../js/slideshow.js"></script>
	<script> var slideIndex = 0; var slides,dots; </script>
</head>

<body>
	<div id="headerformobile">
		<img src="../img/Logo-Background/Aquarea-Logo-short-white.png" alt="Logo Aquarea Vicenza"/>
		<span onclick="openNav()">&#9776; </span>
	</div>
	<div id="slide">
		<div class="slideshow-container">
		<?php
			$db = mysqli_connect('localhost', 'root', '', 'multi_login');
			if (!$db) {
				die(mysql_error());
			}
			$query = "SELECT title, content, Data, img FROM news";
			$results = mysqli_query($db,$query);
			$check = false;
			while($row = mysqli_fetch_array($results)) {
			?>
				<div class="mySlides fade">
					<img class="slideshow-img" src="../img/News/<?php echo $row['img']?>" <?php if($check == false){?> onload="showSlides()" <?php $check = true;}?>>
					<div class="text">
						<h1> <?php echo $row['title']?> </h1>
						<h2 class="text-date"> <?php echo $row['Data']?> </h2>
						<h3> <?php echo $row['content']?> </h3>
					</div>
				</div>
			<?php
			}
			?>
			<a class="prev" onclick="plusSlides(-1)">&#10094;</a> <a class="next" onclick="plusSlides(1)">&#10095;</a> </div>
		</div>
		<div class="dots">	
			<?php
			$i = 1;
			while ($i <= ($results->num_rows)){
			?>
				<span class="dot" onclick="currentSlide(<?php echo $i ?>)"></span>
			<?php
				$i = $i + 1;
			}
			?>
		</div>
	</div>
	<div id="menu">
		<ul id="external">
			<li class="container"><span onclick="openMen()" >Piscina <span class="freccetta">&#9660;</span></span>
				<ul id="piscina" class="overlay"> 
					<li><a href="../html/piscina.html#corsi">Corsi nuoto</a></li>
					<li><a href="../html/piscina.html#agonismo">Agonismo</a></li>
					<li> <a href="../html/piscina.html#nuotolibero">Nuoto Libero</a></li>
					<li xml:lang="en"><a href="../html/piscina.html#altricorsi">Altri Corsi</a></li>
				</ul>
			</li>
			<li class="container"><span onclick="openMen2()" >Palestra <span class="freccetta">&#9660;</span></span>
				<ul id="palestra" class="overlay">	
					<li><a href="../html/palestra.html#corsi"><span xml:lang="en">Planning</span> Terra</a></li>
					<li><a href="../html/palestra.html#corsi">Sala Pesi</a></li>
				</ul>
			</li>
			<li id="lavoraconoi" class="container"><a href="../multi_login/lavoraconoiform.php">Lavora con noi</a></li>
			<li id="chisiamo" class="container"><a href="../html/chisiamo.html">Chi siamo</a></li>
		</ul>
	</div>
	
	<!-- inserito header in fondo perchè così copre tutti gli elementi anche gli altri in absolute (come gli overlay) -->	

	<div id="header">
		<a class="closebtn" onclick="closeNav()">&times;</a>
		<a id="imglogo" href="../multi_login/viest.php">
			<img src="../img/Logo-Background/Aquarea-Logo-short-white.png" alt="Logo Aquarea Vicenza"/>
		</a>
		<a href="../html/piscinescoperte.html" class="headeright">Piscine Scoperte</a>
		<a href="../html/palestra.html" class="headeright">Palestra</a>
		<a href="../html/piscina.html" class="headeright">Piscina</a>
	</div>

	<div id="footer">
		<ul>
			<li>AQUAREA VICENZA EST</li>
			<li>via Zamenhof, 813</li>
			<li>RECAPITI TELEFONICI:</li>
			<li>0444910903,  3427381917</li>
			<li><a href="https://www.facebook.com/AQUAREAVICENZA1/?ref=bookmarks"><img src="../img/Generiche/facebook-icon-png-transparent-logo.png" id="facebook" alt="Logo di Facebook"/></a></li>
		</ul>
		
	</div>
</body>
</html>