<?php 
include('functions.php');
if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Archive system - Remove News</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
</head>
<body>
	<div class="header">
		<h2>Remove News</h2>
	</div>
	
	<form method="post" action="remove_news.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Title</label>
			<input type="text" name="title" value="<?php echo $title; ?>">
		</div>
		<div class="input-group">
			<label>Author</label>
			<input type="text" name="author" value="<?php echo $author; ?>">
		</div>
		<div class="input-group">
			<label>Date</label>
			<input type="date" name="date" value="<?php echo $date; ?>">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="remove_n_btn"> Remove news</button>
			<?php
			if (isAdmin()) {
			?>
				<a href="admin/home.php"> Back </a>
			<?php
			}
			else {
			?>
				<a href="index.php"> Back </a>
			<?php
			}
			?>
		</div>
	</form>
</body>
</html>