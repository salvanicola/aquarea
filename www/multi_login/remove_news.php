<?php 
include('functions.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>Archive system - Remove News</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
		.header {
			background: #003366;
		}
		button[name=register_btn] {
			background: #003366;
		}
	</style>
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
			<a style="margin-left:2%" href="javascript:history.back()"> Back </a>
		</div>
	</form>
</body>
</html>