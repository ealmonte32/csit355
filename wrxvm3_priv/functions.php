<?php
session_start();
require_once('database.php');

//supress mysql warnings for security purposes, but enable only after everything is working on the backend
//ini_set( "display_errors", 0);


//login button action
if (isset($_POST['login_button'])) {
	$login_email = filter_input(INPUT_POST, 'login_email', FILTER_SANITIZE_EMAIL);
	$login_password = filter_input(INPUT_POST, 'login_password');
	$hashed_login_password = sha1($login_password);
	$errors = array();
	login();
}

//the login function
function login() {
	global $db, $login_email, $hashed_login_password, $errors;

	// make sure form is filled properly
	if (empty($login_email)) {
		array_push($errors, "Log error: Email is required");
		echo "Error: Email is required.<br>";
	}
	if (empty($hashed_login_password)) {
		array_push($errors, "Log error: Password is required");
		echo "Error: Password is required.<br>";
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {

		// run the query on the customer table
		$login_check = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$login_email' AND Password='$hashed_login_password'");
		$login_results = $login_check->fetchAll(PDO::FETCH_ASSOC);

		if (count($login_results) == 1) { // if a user with entered email and password match is found

			//return the array results
			foreach ($login_results as $user){
				if ($user['UserType'] == 'customer') {
					$_SESSION['user'] = $user;
					$_SESSION['fname'] = $user['FirstName'];
					$_SESSION['address'] = $user['Address'];
					$_SESSION['vehiclemodel'] = $user['VehicleModel'];
					$_SESSION['vehicleyear'] = $user['VehicleYear'];
					$_SESSION['telephone'] = $user['Telephone'];
					header("location: customer_dashboard.php");
				}
			}

		} // if no user is found or password doesnt match
		else {
			echo "<center><h4>(Error): User Not Found or Incorrect Password.</h4></center>";
		}
	}
}

// destroy and unset session if user clicks log out, then send back to login page
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

?>