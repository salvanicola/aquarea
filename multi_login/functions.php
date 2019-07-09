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
		array_push($errors, "Username obbligatorio"); 
	}
	if (already('users','username',$username)) {
		array_push($errors, "Username già utilizzato");
	}
	if (preg_match('/\W/', $username))
	{
		array_push($errors, "L'Username deve contenere solo numeri e lettere"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email obbligatoria"); 
	}		
	else if (already('users','email',$email)) {
		array_push($errors, "Esiste già un altro account con questa Email");
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email non valida"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password obbligatoria"); 
	}
	if (!preg_match('/^\S{8,15}$/', $password_1))
	{
		array_push($errors, "La Password deve contenere tra gli 8 ed i 15 caratteri VALIDI (niente spazi)"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "Le password non corrispondono");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database $password = md5($password_1);

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "Nuovo utente creato con successo!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "Adesso sei autenticato";
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
		array_push($errors, "Titolo obbligatorio"); 
	}
	if (empty($content)) { 
		array_push($errors, "Content obbligatorio"); 
	}
	else if(strlen($content)>150)
	{
		array_push($errors, "Il Content può essere al massimo di 150 caratteri"); 
	}
	if (empty($author)) { 
		array_push($errors, "Autore obbligatorio"); 
	}
	else if(!already('users','username', $author))
	{
		array_push($errors, "Tale Autore non esiste");
	}
	if (empty($date)) { 
		array_push($errors, "Data obbligatoria"); 
	}
	if ($_FILES['fileToUpload']['size'] == 0) {
		array_push($errors, "File obbligatorio");
	} else {
		$check = getimagesize($_FILES['fileToUpload']['tmp_name']);
		list($width, $height) = $check;
		if($check !== false) {
			$uploadOk = 1;
		} else {
			array_push($errors, "Il File non è un'immagine");
			$uploadOk = 0;
		}
		$counter = 0;
		while (file_exists($target_file)) {
			$rawBaseName = pathinfo($target_file, PATHINFO_FILENAME );
			$ext = pathinfo($target_file, PATHINFO_EXTENSION);
			$target_file = $target_dir . $rawBaseName . "(" . $counter . ")" . "." . $ext;
			$counter++;
		}
		if ($_FILES["fileToUpload"]["size"] > 1000000) {
			array_push($errors, "Il File è troppo grande.");
			$uploadOk = 0;
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
			array_push($errors, "Solo JPG, JPEG, PNG e GIF sono validi formati.");
			$uploadOk = 0;
		}
		if($width != 1920 || $height != 784)
		{
			array_push($errors, "La risoluzione della tua immagine non è valida ''(''deve essere 1920x784'')''");
			$uploadOk = 0;
		}
	}
    
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0 AND count($errors) == 0) {
		array_push($errors, "L'upload non è andato a buon fine.");
		// if everything is ok, try to upload file
	} else if(count($errors) == 0){
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    } else {
		array_push($errors, "L'upload non è andato a buon fine");
    }
}
	if (count($errors) == 0) {
		$img = pathinfo($target_file, PATHINFO_FILENAME) . "." . pathinfo($target_file, PATHINFO_EXTENSION);
		$query = "INSERT INTO news (title, content, author, Data, img) 
					 VALUES('$title', '$content', '$author', '$date', '$img')";
		mysqli_query($db, $query);
		$_SESSION['success']  = "News creata con successo!!";
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
		array_push($errors, "Nome obbligatorio"); 
	}
	if (empty($surname)) { 
		array_push($errors, "Cognome obbligatorio"); 
	}
	if (empty($date)) { 
		array_push($errors, "Data obbligatoria"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email obbligatoria"); 
	}
	else if (already('requests','Email',$email)) {
		array_push($errors, "Un'altra richiesta da questa Email esiste già");
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email non valida"); 
	}
	if ($_FILES['pdfToUpload']['size'] == 0) {
		array_push($errors, "File obbligatorio");
	} else {
		$check = filesize($_FILES['pdfToUpload']['tmp_name']);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			array_push($errors, "Il File non è in formato PDF");
			$uploadOk = 0;
		}
		if ($_FILES["pdfToUpload"]["size"] > 500000) {
			array_push($errors, "Il tuo File è troppo grande.");
			$uploadOk = 0;
		}
		if($FileType != "pdf") {
			array_push($errors, "Sono permessi solo file di tipo PDF");
			$uploadOk = 0;
		}
		$counter = 0;
		while (file_exists($target_file)) {
			$rawBaseName = pathinfo($target_file, PATHINFO_FILENAME );
			$ext = pathinfo($target_file, PATHINFO_EXTENSION);
			$target_file = $target_dir . $rawBaseName . "(" . $counter . ")" . "." . $ext;
			$counter++;
		}
	}
    
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0 AND count($errors) == 0) {
		array_push($errors, "L'Upload non è andato a buon fine");
		// if everything is ok, try to upload file
	} else if(count($errors) == 0){
		if (move_uploaded_file($_FILES["pdfToUpload"]["tmp_name"], $target_file)) {
		} else {
		array_push($errors, "L'Upload non è andato a buon fine");
		}
	}
	if (count($errors) == 0) {
		$cv = pathinfo($target_file, PATHINFO_FILENAME) . "." . pathinfo($target_file, PATHINFO_EXTENSION);
		$query = "INSERT INTO requests (name, surname, date, email, Sesso, note, cv) 
					 VALUES('$name', '$surname', '$date', '$email', '$sesso', '$note', '$cv')";
		mysqli_query($db, $query);
		$_SESSION['success'] = "Richiesta inviata con successo!!";
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
		array_push($errors, "Username obbligatorio"); 
	}
	if (preg_match('/\W/', $username))
	{
		array_push($errors, "Username contiene solo numeri e lettere"); 
	}
	if ($_SESSION['user']['username'] == $username) {
		array_push($errors, "Non puoi eliminare il tuo stesso account");
	}
	if (empty($email)) { 
		array_push($errors, "Email obbligatoria"); 
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email non valida"); 
	}
	if (count($errors) == 0) {
		if (!already_2('users','username','email', $username, $email)) {
		array_push($errors, "Tale Utente non esiste");
		}
	}
	if (count($errors) == 0) {
		$query = "DELETE FROM users  
				  WHERE username = '$username' AND email = '$email'";
		mysqli_query($db, $query);
		$_SESSION['success']  = "Utente rimosso con successo!!";
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
		array_push($errors, "Titolo obbligatorio"); 
	}
	if (empty($author)) { 
		array_push($errors, "Autore obbligatorio"); 
	}
	if (!already_3('news','title','author','Data', $title, $author, $date)) {
		array_push($errors, "Tale news non esiste");
	}
	if (empty($date)) { 
		array_push($errors, "Data obbligatoria"); 
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
		(unlink("../img/News/$cleanup") and $_SESSION['success']  = "News rimossa con successo!!") or $_SESSION['success']  = "News rimossa con successo, ma la sua immagine non era in archivio";
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
		array_push($errors, "Nome obbligatorio"); 
	}
	if (empty($surname)) { 
		array_push($errors, "Cognome obbligatorio"); 
	}
	if (empty($email)) { 
		array_push($errors, "Email obbligatoria"); 
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		array_push($errors, "Email non valida"); 
	}
	if (count($errors) == 0) {
		if (!already_3('requests','name','surname','email', $name, $surname, $email)) {
		array_push($errors, "Tale Richiesta non esiste");
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
		$_SESSION['success']  = "Richiesta rimossa con successo!!";
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
		array_push($errors, "Username obbligatorio");
	}
	if (empty($password)) {
		array_push($errors, "Password obbligatoria");
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
				$_SESSION['success']  = "Sei autenticato";
				header('location: admin/home.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "Sei autenticato";

				header('location: index.php');
			}
		}else {
			array_push($errors, "Errata combinazione Username/Password");
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