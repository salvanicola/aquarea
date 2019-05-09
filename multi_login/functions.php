<?php 
session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'multi_login');

// variable declaration
$username = "";
$email    = "";
$errors   = array(); 
$title    = "";
$content  = "";
$author   = "";
$date     = "0000-00-00";
$name     =  "";
$surname  =  "";
$note     = "";
$cv 	  = "";
$output   = "";

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
if (isset($_POST['request_btn'])) {
	request_c();
}
if (isset($_POST['reject_btn'])) {
	reject_request();
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
	if (already('users','username',$username)) {
		array_push($errors, "Username is already taken");
	}
	if (preg_match('/\W/', $username))
	{
		array_push($errors, "Username must contain only numbers or letters"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}		
	else if (already('users','email',$email)) {
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
		$password = $password_1;//encrypt the password before saving in the database $password = md5($password_1); cercare perchè in questo formato effettua due criptazioni

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
	global $db, $errors, $title, $content, $author, $date, $img;

	$title    =  e($_POST['title']);
	$content  =  e($_POST['content']);
	$author   =  e($_POST['author']);
	$date     =  e($_POST['date']);

	$target_dir = "../img/News/";
	$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
	if (empty($title)) { 
		array_push($errors, "Title is required"); 
	}
	if (empty($content)) { 
		array_push($errors, "Content is required"); 
	}
	else if(strlen($content)>150)
	{
		array_push($errors, "Content can have 150 characters max"); 
	}
	if (empty($author)) { 
		array_push($errors, "Author is required"); 
	}
	else if(!already('users','username', $author))
	{
		array_push($errors, "Such Author does not exist");
	}
	if (empty($date)) { 
		array_push($errors, "Date is required"); 
	}
	if ($_FILES['fileToUpload']['size'] == 0) {
		array_push($errors, "File is required");
	} else {
		$check = getimagesize($_FILES['fileToUpload']['tmp_name']);
		list($width, $height) = $check;
		if($check !== false) {
			$uploadOk = 1;
		} else {
			array_push($errors, "File is not an image");
			$uploadOk = 0;
		}
		if (file_exists($target_file)) {
			array_push($errors, "Sorry, file already exists."); 
			$uploadOk = 0;
		}
		if ($_FILES["fileToUpload"]["size"] > 1000000) {
			array_push($errors, "Sorry, your file is too large.");
			$uploadOk = 0;
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
			array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
			$uploadOk = 0;
		}
		if($width != 1920 || $height != 784)
		{
			array_push($errors, "Sorry, your image resolution is not correct ''(''must be 1920x784'')''");
			$uploadOk = 0;
		}
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
		$img = $_FILES["fileToUpload"]["name"];
		$query = "INSERT INTO news (title, content, author, Data, img) 
					 VALUES('$title', '$content', '$author', '$date', '$img')";
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

function request_c(){
	global $db, $errors, $name, $surname, $date, $email, $note, $cv, $sesso;

	$name    =  e($_POST['name']);
	$surname =  e($_POST['surname']);
	$date  	 =  e($_POST['date']);
	$email   =  e($_POST['email']);
	$sesso	 =  e($_POST['sesso']);
	$note    =  e($_POST['note']); 

	$target_dir = "documents/curriculum/";
	$target_file = $target_dir . basename($_FILES['pdfToUpload']['name']);
	$uploadOk = 1;
	$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		
	if (empty($name)) { 
		array_push($errors, "Name is required"); 
	}
	if (empty($surname)) { 
		array_push($errors, "Surname is required"); 
	}
	if (empty($date)) { 
		array_push($errors, "Date is required"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	else if (already('requests','Email',$email)) {
		array_push($errors, "A request from this Email already exist");
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email is not valid"); 
	}
	if ($_FILES['pdfToUpload']['size'] == 0) {
		array_push($errors, "File is required");
	} else {
		$check = filesize($_FILES['pdfToUpload']['tmp_name']);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			array_push($errors, "File is not a PDF");
			$uploadOk = 0;
		}
		if ($_FILES["pdfToUpload"]["size"] > 500000) {
			array_push($errors, "Sorry, your file is too large.");
			$uploadOk = 0;
		}
		if($FileType != "pdf") {
			array_push($errors, "Sorry, only PDF files are allowed.");
			$uploadOk = 0;
		}
	}
    
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0 AND count($errors) == 0) {
		array_push($errors, "Sorry, your file was not uploaded.");
		// if everything is ok, try to upload file
	} else if(count($errors) == 0){
		if (move_uploaded_file($_FILES["pdfToUpload"]["tmp_name"], $target_file)) {
		} else {
		array_push($errors, "Sorry, there was an error uploading your file.");
		}
	}
	if (count($errors) == 0) {
		$cv = $_FILES["pdfToUpload"]["name"];
		$query = "INSERT INTO requests (name, surname, date, email, Sesso, note, cv) 
					 VALUES('$name', '$surname', '$date', '$email', '$sesso', '$note', '$cv')";
		mysqli_query($db, $query);
		$_SESSION['success'] = "Request successfully sent!";
		header('location: lavoraconoiform.php');
		exit(0);
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
	if ($_SESSION['user']['username'] == $username) {
		array_push($errors, "You can't delete your own account");
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email is not valid"); 
	}
	if (count($errors) == 0) {
		if (!already_2('users','username','email', $username, $email)) {
		array_push($errors, "Such combination of User/Email do not exist");
		}
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
	if (!already_3('news','title','author','Data', $title, $author, $date)) {
		array_push($errors, "Such news do not exist");
	}
	if (empty($date)) { 
		array_push($errors, "Date is required"); 
	}
	if (count($errors) == 0) {
		$query = "SELECT img FROM news
				  WHERE title = '$title' AND author = '$author' AND Data = '$date'";
		$result = mysqli_query($db, $query);
		$result_a = mysqli_fetch_assoc($result);
		$cleanup = implode($result_a);
		$query = "DELETE FROM news  
				  WHERE title = '$title' AND author = '$author' AND Data = '$date'";
		mysqli_query($db, $query);
		unlink("../img/News/$cleanup") or die("Couldn't delete file");
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

function reject_request(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $name, $surname, $email, $result, $result_a, $cleanup;
	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$name    =  e($_POST['name']);
	$surname =  e($_POST['surname']);
	$email       =  e($_POST['email']);

	// form validation: ensure that the form is correctly filled
	if (empty($name)) { 
		array_push($errors, "Name is required"); 
	}
	if (empty($surname)) { 
		array_push($errors, "Surname is required"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email is not valid"); 
	}
	if (count($errors) == 0) {
		if (!already_3('requests','name','surname','email', $name, $surname, $email)) {
		array_push($errors, "Such Request do not exist");
		}
	}
	if (count($errors) == 0) {
		$query = "SELECT cv FROM requests 
				  WHERE name = '$name' AND surname = '$surname' AND email = '$email'";
		$result = mysqli_query($db, $query);
		$result_a = mysqli_fetch_assoc($result);
		$cleanup = implode($result_a);
		$query = "DELETE FROM requests
				  WHERE name = '$name' AND surname = '$surname' AND email = '$email'";
		mysqli_query($db, $query);
		unlink("documents/curriculum/$cleanup") or die("Couldn't delete file");
		$_SESSION['success']  = "Request successfully rejected!!";
		if(isAdmin())
		{
			header('location: admin/home.php');
		}
		else
		{
			header('location: ../multi_login/index.php');
		}
		// get id of the created user
		$logged_in_user_id = mysqli_insert_id($db);		
	}
}

function already($table, $field, $value){
	global $db;
	$query = "SELECT * FROM $table WHERE $field = '$value'";
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

function already_3($table, $field_1, $field_2, $field_3, $value_1, $value_2, $value_3){		//da migliorare
	global $db;
	$query = "SELECT * FROM $table WHERE $field_1 = '$value_1' AND $field_2 = '$value_2' AND $field_3 = '$value_3'";
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
			if ($logged_in_user['user_type'] == 'Admin') {

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
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'Admin' ) {
		return true;
	}else{
		return false;
	}
}