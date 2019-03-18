<?php 
session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'multi_login');

// variable declaration
$username = "";
$email    = "";
$errors   = array(); 
$title    = "";
$subtitle = "";
$content  = "";
$author   = "";
$date     = "0000-00-00";
$URL 	  = "";

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}
if (isset($_POST['remove_btn'])) {
	remove();
}
if (isset($_POST['register_n_btn'])) {
	register_n();
}
if (isset($_POST['remove_n_btn'])) {
	remove_n();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

		if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (already('username',$username)) {
		array_push($errors, "Username is already taken");
	}
	if (preg_match('/\W/', $username))
	{
		array_push($errors, "Username must contain only numbers or letters"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (already('email',$email)) {
		array_push($errors, "An account with this Email already exist");
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email is not valid"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if (!preg_match('/^\S{8,15}$/', $password_1))
	{
		array_push($errors, "Password must be between 8 and 15 VALID characters (no spaces)"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');				
		}
	}
}

function register_n(){
	global $db, $errors, $title, $subtitle, $content, $author, $date, $URL;

	$title    =  e($_POST['title']);
	$subtitle =  e($_POST['subtitle']);
	$content  =  e($_POST['content']);
	$author   =  e($_POST['author']);
	$date     =  e($_POST['date']);
	$URL	  =  e($_POST['URL']);

	$target_dir = "images/News/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	
	if (empty($title)) { 
		array_push($errors, "Title is required"); 
	}
	if (empty($content)) { 
		array_push($errors, "Content is required"); 
	}
	if (empty($author)) { 
		array_push($errors, "Author is required"); 
	}
	if (empty($date)) { 
		array_push($errors, "Date is required"); 
	}
	if (empty($URL)) { 
		array_push($errors, "URL is required"); 
	}
	if (!filter_var($URL, FILTER_VALIDATE_URL))
	{
		array_push($errors, "URL is not valid"); 
	}
    if($check !== false) {
        $uploadOk = 1;
    } else {
        array_push($errors, "File is not an image."); 
        $uploadOk = 0;
    }
	// Check if file already exists
	if (file_exists($target_file)) {
		array_push($errors, "Sorry, file already exists."); 
		$uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
		array_push($errors, "Sorry, your file is too large.");
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
		array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0 AND count($errors) == 0) {
		array_push($errors, "Sorry, your file was not uploaded.");
		// if everything is ok, try to upload file
	} else if(count($errors) == 0){
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    } else {
		array_push($errors, "Sorry, there was an error uploading your file.");
    }
}
	if (count($errors) == 0) {
		$query = "INSERT INTO news (id, title, subtitle, content, author, rilascio, URL) 
					 VALUES('0','$title', '$subtitle', '$content', '$author', '$date', '$URL')";
		mysqli_query($db, $query);
		$_SESSION['success']  = "News successfully created!!";
		if(isAdmin())
		{
			header('location: admin/home.php');
		}
		else
		{
			header('location: ../multi_login/index.php');
		}
	}
}

function remove(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;
	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (preg_match('/\W/', $username))
	{
		array_push($errors, "Usernames contain only numbers or letters"); 
	}
	if (!already_2('users','username','email', $username, $email)) {
		array_push($errors, "Such combination of User/Email do not exist");
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email is not valid"); 
	}
	if (count($errors) == 0) {
		$query = "DELETE FROM users  
				  WHERE username = '$username' AND email = '$email'";
		mysqli_query($db, $query);
		$_SESSION['success']  = "User successfully removed!!";
		header('location: home.php');

		// get id of the created user
		$logged_in_user_id = mysqli_insert_id($db);			
	}
}

function remove_n(){
	global $db, $errors, $title, $author, $date;
	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$title    =  e($_POST['title']);
	$author   =  e($_POST['author']);
	$date     =  e($_POST['date']);

	// form validation: ensure that the form is correctly filled
	if (empty($title)) { 
		array_push($errors, "Title is required"); 
	}
	if (empty($author)) { 
		array_push($errors, "Author is required"); 
	}
	if (!already_2('news','title','author', $title, $author)) {
		array_push($errors, "Such news do not exist");
	}
	if (empty($date)) { 
		array_push($errors, "Date is required"); 
	}
	if (count($errors) == 0) {
		$query = "DELETE FROM news  
				  WHERE title = '$title' AND author = '$author' AND rilascio = '$date'";
		mysqli_query($db, $query);
		$_SESSION['success']  = "News successfully removed!!";
		if(isAdmin())
		{
			header('location: admin/home.php');
		}
		else
		{
			header('location: ../multi_login/index.php');
		}
	}
}

function already($field, $value){
	global $db;
	$query = "SELECT * FROM users WHERE $field = '$value'";
	$result = mysqli_query($db, $query);
	if($result->num_rows == 0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function already_2($table, $field_1, $field_2, $value_1, $value_2){		//da migliorare
	global $db;
	$query = "SELECT * FROM $table WHERE $field_1 = '$value_1' AND $field_2 = '$value_2'";
	$result = mysqli_query($db, $query);
	if($result->num_rows == 0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin/home.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}