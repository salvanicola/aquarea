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
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
	.header {
		background: #003366;
	}
	</style>
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
						unset($_SESSION['success']);
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
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="home.php?logout='1'" style="color: red;">logout</a> &nbsp; 
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="header" >
		<h2> Gestione utenti </h2>
	</div>
	<div class="content">
		<a class="btn" style="background:#003366" href="create_user.php"> Add User</a>
		<a class="btn" style="background:#003366" href="remove_user.php"> Remove User</a>
		<br>
		<br>
		<table border="1">
        <thead>
            <tr>
                <th>Username</td>
				<th>Email</td>
				<th>User Level</td>
            </tr>
        </thead>
        <tbody>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'multi_login');
            if (!$db) {
                die(mysql_error());
            }
			$query = "SELECT username, email, user_type FROM users";
            $results = mysqli_query($db,$query);
            while($row = mysqli_fetch_array($results)) {
            ?>
                <tr>
                    <td><?php echo $row['username']?></td>
					<td><?php echo $row['email']?></td>
					<td><?php echo $row['user_type']?></td>
                </tr>

            <?php
            }
            ?>
            </tbody>
            </table>
	</div>
	<div class="header" >
		<h2> Gestione notizie </h2>
	</div>
	<div class="content">
		<a class="btn" style="background:#003366" href="../create_news.php"> Add News</a>
		<a class="btn" style="background:#003366" href="../remove_news.php"> Remove News</a>
		<br>
		<br>
		<table border="1">
        <thead>
            <tr>
                <th>Titolo</td>
				<th>Autore</td>
				<th>Data Pubblicazione</td>
				<th>URL</td>
            </tr>
        </thead>
        <tbody>
        <?php
            $db = mysqli_connect('localhost', 'root', '', 'multi_login');
            if (!$db) {
                die(mysql_error());
            }
			$query = "SELECT title, author, Data, URL FROM news";
            $results = mysqli_query($db,$query);
            while($row = mysqli_fetch_array($results)) {
            ?>
                <tr>
                    <td><?php echo $row['title']?></td>
					<td><?php echo $row['author']?></td>
					<td><?php echo $row['Data']?></td>
					<td> <a href="<?php echo $row['URL']?>"><?php echo $row['URL']?> </a></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
	</div>
</body>
</html>