<?php 
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
</head>
<body>
	<div class="header">
		<h2>Admin - Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['success'];
						unset ($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
		<div class="profile_info">
			<img src="../images/admin_profile.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i>(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="home.php?logout='1'">logout</a> &nbsp; 
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="header" >
		<h2> Gestione utenti </h2>
	</div>
	<div class="content">
		<div class="center-btn">
			<a class="btn" href="create_user.php"> Add User</a>
			<a class="btn" href="remove_user.php"> Remove User</a>
		</div>
		<br>
		<br>
		<ul class="center">
			<li class="side-by-side-1"><strong> Username </strong></li>
			<li class="side-by-side-1"><strong> Email </strong></li>
			<li class="side-by-side-1"><strong> User Level </strong></li>
		</ul>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'multi_login');
            if (!$db) {
                die(mysql_error());
            }
			$query = "SELECT username, email, user_type FROM users";
            $results = mysqli_query($db,$query);
			if ($results->num_rows == 0)
			{?>
				<ul class="center">
					<li class="side-by-side-1"><?php echo "Nessun utente trovato"?></li>
				</ul>
			<?php
			}
            while($row = mysqli_fetch_array($results)) {
            ?>
				<ul class="center">
					<li class="side-by-side-1"><?php echo $row['username']?></li>
					<li class="side-by-side-1"><?php echo $row['email']?></li>
					<li class="side-by-side-1"><?php echo $row['user_type']?></li>
				</ul>
            <?php
            }
            ?>
	</div>
	<div class="header" >
		<h2> Gestione notizie </h2>
	</div>
	<div class="content">
		<div class="center-btn">
			<a class="btn" href="../create_news.php"> Add News</a>
			<a class="btn" href="../remove_news.php"> Remove News</a>
		</div>
		<br>
		<br>
		<ul class="center">
			<li class="side-by-side-2"><strong> Titolo </strong></li>
			<li class="side-by-side-2"><strong> Autore </strong></li>
			<li class="side-by-side-2"><strong> Data Pubblicazione </strong></li>
			<li class="side-by-side-2"><strong> Image </strong></li>
		</ul>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'multi_login');
            if (!$db) {
                die(mysql_error());
            }
			$query = "SELECT title, author, Data, img FROM news";
            $results = mysqli_query($db,$query);
			if ($results->num_rows == 0)
			{?>
				<ul class="center">
					<li class="side-by-side-1"><?php echo "Nessuna news trovata"?></li>
				</ul>
			<?php
			}
            while($row = mysqli_fetch_array($results)) {
            ?>
				<ul class="center">
					<li class="side-by-side-2"><?php echo $row['title']?></li>
					<li class="side-by-side-2"><?php echo $row['author']?></li>
					<li class="side-by-side-2"><?php echo $row['Data']?></li>
					<li class="side-by-side-2"><a href="../../img/News/<?php echo $row['img']?>">View image</a></li>
				</ul>
            <?php
            }
            ?>
	</div>
	<div class="header" >
		<h2> Gestione richieste </h2>
	</div>
	<div class="content">
		<div class="center-btn">
			<a class="btn" href="../remove_request.php"> Remove Request</a>
		</div>
		<br>
		<br>
        <ul class="center">
			<li class="side-by-side-3"><strong> Nome </strong></li>
			<li class="side-by-side-3"><strong> Cognome </strong></li>
			<li class="side-by-side-3 Sesso"><strong> Sesso </strong></li>
			<li class="side-by-side-3 DataNascita"><strong> Data di Nascita </strong></li>
			<li class="side-by-side-3 email"><strong> Email </strong></li>
			<li class="side-by-side-3"><strong> Curriculum </strong></li>
		</ul>
        <tbody>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'multi_login');
            if (!$db) {
                die(mysql_error());
            }
			$query = "SELECT name, surname, date, email, Sesso, cv FROM requests";
            $results = mysqli_query($db,$query);
			if ($results->num_rows == 0)
			{?>
				<ul class="center">
					<li class="side-by-side-3"><?php echo "Nessuna richiesta trovata"?></li>
				</ul>
			<?php
			}
            while($row = mysqli_fetch_array($results)) {
            ?>
				<ul class="center">
					<li class="side-by-side-3"><?php echo $row['name']?></li>
					<li class="side-by-side-3"><?php echo $row['surname']?></li>
					<li class="side-by-side-3 Sesso"><?php if($row['Sesso'] == "Maschio"){ echo "M";} else if ($row['Sesso'] == "Femmina"){ echo "F";}?></li>
					<li class="side-by-side-3 DataNascita"><?php echo $row['date']?></li>
					<li class="side-by-side-3 email"><?php echo $row['email']?></li>
					<li class="side-by-side-3"><a href="../documents/curriculum/<?php echo $row['cv']?>">Download</a></li>
				</ul>
            <?php
            }
            ?>
	</div>
</body>
</html>