<?php 
include('functions.php');

?>

<!DOCTYPE html>
<html>
<head>
	<title>Archive system - Add News</title>
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
		<h2>Add News</h2>
	</div>
	
	<form method="post" action="create_news.php" enctype="multipart/form-data">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Title</label>
			<input type="text" name="title" value="<?php echo $title; ?>">
		</div>
		<div class="input-group">
			<label>Subtitle</label>
			<input type="text" name="subtitle" value="<?php echo $subtitle; ?>">
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
				selector: '#content'
			});
			</script>
		</div>
		<div class="input-group">
			<label>Author</label>
			<input type="text" name="author" value="<?php echo $author; ?>">
		</div>
		<div class="input-group">
			<label>Date</label>
			<input type="date" name="date" value="<?php echo $date; ?>">
		</div class="input-group">
		<div class="input-group">
			<label>URL</label>
			<input type="text" name="URL" value="<?php echo $URL; ?>">
		</div>
		<div>
			Select image to upload:
			<input type="file" name="fileToUpload" id="fileToUpload">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="register_n_btn">Create News</button>
			<a style="margin-left:2%" href="javascript:history.back()"> Back </a>
		</div>
	</form>
</body>
</html>
