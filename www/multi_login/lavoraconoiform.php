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
	<link rel="stylesheet" type="text/css" href="../css/firstlevel.css" media="handheld, screen"/> 
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat"> 
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>
	<div id="header">
		<a href="viest.html" id="imglogo">
			<img src="../img/Logo-Background/Aquarea-Logo-short-white.png" id="LogoAquarea" alt="Logo Aquarea Vicenza"/>
		</a>
		<a href="eventi.html" class="headeright">Eventi</a>
		<a href="piscinescoperte.html" class="headeright">Piscine Scoperte</a>
		<a class="headeright, active">Vicenza Est</a>
	</div>

	<div>
		<p>SE VUOI LAVORARE CON NOI INVIA LA TUA DISPONIBILITA'</br>E IL TUO CURRICULUM VIA MAIL A:</br>CV.AQUAREA@GMAIL.COM</br>O CONSEGNALO PERSONALMENTE PRESSO IL NOSTRO IMPIANTO </br> </p>
	</div>
	<div class="header">
		<h2>Invia il tuo Curriculum</h2>
	</div>
	
	<form method="post" action="create_news.php" enctype="multipart/form-data">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Nome</label>
			<input type="text" name="title" value="<?php echo $title; ?>">
		</div>
		<div class="input-group">
			<label>Cognome</label>
			<input type="text" name="subtitle" value="<?php echo $subtitle; ?>">
		</div>
		<div class="input-group">
			<label>Data di Nascita</label>
			<input type="date" name="date" value="<?php echo $date; ?>">
		</div class="input-group">
		<div class="input-group">
			<label>Sesso</label>
			<select name="user_type" id="user_type" >
				<option value="admin">Maschio</option>
				<option value="mod">Femmina</option>
			</select>
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<!-- da aggiungere il conferma email -->
		<div class="input-group">
			<label>Note aggiuntive</label>
			<textarea name="content" rows=4 cols=102><?php echo $content; ?></textarea>
		</div>
		<div>
			Seleziona il file .pdf del tuo CV:
			<input type="file" name="fileToUpload" id="fileToUpload">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="register_n_btn">Send Request</button>
		</div>
	</form>
<!-- inserito header in fondo perchè così copre tutti gli elementi anche gli altri in absolute (come gli overlay) -->
	<div id="footer">
		<ul>
			<li>AQUAREA VICENZA EST</li>
			<li>via Zamenhof, 813</li>
			<li>RECAPITI TELEFONICI:</li>
			<li>0444910903,  3427381917</li>
			<a href="https://www.facebook.com/AQUAREAVICENZA1/?ref=bookmarks"><img src="../img/Generiche/facebook-icon-png-transparent-logo.png" id="facebook" alt="Logo di Facebook"/></a>
		</ul>	
	</div>
</body>
