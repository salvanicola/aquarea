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
	<title>Archive system - Add News</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="shortcut icon" href="../favicon.ico" />
</head>
<body>
	<div class="header">
		<h2>Add News</h2>
	</div>
	
	<form method="post" action="create_news.php" enctype="multipart/form-data">

		<?php echo display_error(); ?>
		
		<div class="input-group">
			<label>Title</label>
			<input type="text" name="title" value="<?php echo $title; ?>" required>
		</div>
		<div class="input-group">
			<label>Content</label>
			<textarea name="content" id="content">
				<?php if(isset($_POST['content'])) {  
				echo $_POST['content']; }?>
			</textarea>
			<script src='tinymce/tinymce.min.js'></script>
			<script>
			tinymce.init({
				selector: '#content',
				theme: 'silver',
				mobile: {
					theme: 'mobile',
					plugins: [ 'autosave', 'lists', 'autolink' ],
					toolbar: [ 'undo', 'bold', 'italic', 'styleselect' ]
				}
			});
			</script>
		</div>
		<div class="input-group">
			<label>Author</label>
			<input type="text" name="author" value="<?php echo $author; ?>" required>
		</div>
		<div class="input-group">
			<label>Date</label>
			<input type="date" name="date" value="<?php echo $date; ?>" required>
		</div>
		<div class="input-group igf">
			<label>Select image to upload (must be 1920x784):</label>
			<input type="file" class="upload-img" name="fileToUpload" id="fileToUpload" accept="image/jpeg" required> 
		</div>
		<div class="input-group center-btn">
			<button type="submit" class="btn" name="register_n_btn">Create News</button>
			</br>
			</br>
			<?php
			if (isAdmin()) {
			?>
				<a class="back" href="admin/home.php"> Back </a>
			<?php
			}
			else {
			?>
				<a class="back" href="index.php"> Back </a>
			<?php
			}
			?>
		</div>
	</form>
</body>
</html>
