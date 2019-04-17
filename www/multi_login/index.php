<?php 
	include('functions.php');
	if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
	}
	
	if (isAdmin()) {
	$_SESSION['msg'] = "Redirecting";
	header('location: /multi_login/admin/home.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['success']; 
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>
		<!-- logged in user information -->
		<div class="profile_info">
			<img src="images/user_profil.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="index.php?logout='1'" style="color: red;">logout</a>
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="header" >
		<h2> Gestione notizie </h2>
	</div>
	<div class="content">
		<a class="btn" style="background:#003366" href="create_news.php"> Add News</a>
		<a class="btn" style="background:#003366" href="remove_news.php"> Remove News</a>
		<br>
		<br>
		<table border="1">
        <thead>
            <tr>
                <th>Titolo</td>
				<th>Autore</td>
				<th>Data Pubblicazione</td>
            </tr>
        </thead>
        <tbody>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'multi_login');
            if (!$db) {
                die(mysql_error());
            }
			$query = "SELECT title, author, Data FROM news";
            $results = mysqli_query($db,$query);
            while($row = mysqli_fetch_array($results)) {
            ?>
                <tr>
                    <td><?php echo $row['title']?></td>
					<td><?php echo $row['author']?></td>
					<td><?php echo $row['Data']?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
	</div>
</body>
</html>