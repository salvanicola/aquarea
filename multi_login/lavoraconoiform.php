<?php 
	include('functions.php');
?>

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
	<link rel="stylesheet" type="text/css" href="../css/mobile3level.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../css/test.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../css/firstlevel.css" media="handheld, screen and (min-device-width:600px)"/> 
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat"> 
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-device-width:600px)"/>
	<script type="text/javascript" src="../js/scriptsidebar.js"></script>
</head>


<body>
		<div id="headerformobile">
		<a id="imglogo" href="../multi_login/viest.php">
		<img src="../img/Logo-Background/Aquarea-Logo-short-white.png" alt="Logo Aquarea Vicenza"/>
		</a>
		<span onclick="openNav()">&#9776; </span>
		</div>
	<div id="header">
		<a class="closebtn" onclick="closeNav()">&times;</a>
		<a id="imglogo" href="../multi_login/viest.php">
			<img src="../img/Logo-Background/Aquarea-Logo-short-white.png" alt="Logo Aquarea Vicenza"/>
		</a>
		<a href="../html/piscinescoperte.html" class="headeright">Piscine Scoperte</a>
		<a href="../html/palestra.html" class="headeright">Palestra</a>
		<a href="../html/piscina.html" class="headeright">Piscina</a>
	</div>

	<div class="header">
		<h2>Richiedi un colloquio</h2>
	</div>
	
	
	<form method="post" action="lavoraconoiform.php" enctype="multipart/form-data">
		<?php echo display_error(); ?>
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) { ?>
			<div class="error success" >
				<h3>
					<?php
						echo $_SESSION['success'];
						unset ($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php
		} 
		?>
	<div class="formobile">
		<div class="input-group">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label>Cognome</label>
			<input type="text" name="surname" value="<?php echo $surname; ?>">
		</div>
		<div class="input-group">
			<label>Data di Nascita</label>
			<input type="date" name="date" value="<?php echo $date; ?>">
		</div class="input-group">
		<div class="input-group">
			<label>Sesso</label>
			<select name="sesso" id="sesso" >
				<option value="Maschio" class="sex">Maschio</option>
				<option value="Femmina" class="sex">Femmina</option>
			</select>
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<label>Note aggiuntive</label>
			<input type="note" name="note" value="<?php echo $note; ?>">
		</div>
		<div class="normal">
			<label>Seleziona il file .pdf del tuo CV:</label>
			<input type="file" name="pdfToUpload" id="pdfToUpload">
		<div class="input-group">
			<button type="submit" class="btn" name="request_btn">Send Request</button>
        </div>
		</div>
	</div>
	</form>
<!-- inserito header in fondo perchè così copre tutti gli elementi anche gli altri in absolute (come gli overlay) -->
	<br>
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