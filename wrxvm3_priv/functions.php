<?php
session_start();
require_once('database.php');

//supress mysql warning if user is not found but still echo the warning
//ini_set( "display_errors", 0);

// declare login variables
//$login_email = filter_input(INPUT_POST, 'login_email', FILTER_SANITIZE_EMAIL);
//$login_password = filter_input(INPUT_POST, 'login_password');
//$errors = array();

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
		$login_results = $login_check->fetchAll();
		//var_dump($login_results);

		// check customers table
		//$query = 'SELECT * FROM almontee3_EMPLOYEES WHERE Email=".$user." AND Password=".$pass." LIMIT 1';
		//$results = @mysqli_query($db, $query); // we suppress the results query so that we dont get mysql warning on screen

		//if ($login_results == 1) { // if a user with entered email and password match is found
		if (count($login_results) == 1) {

			// check what user logged on and send to specific protected page
			$logged_in_user = $login_results;

			if ($logged_in_user['UserType'] == 'dbadmin') {
				$_SESSION['user'] = $logged_in_user;
				header("location: ../wrxvm3_priv/db_manager.php");		  
			}
			// this is for business manager
			elseif ($logged_in_user['UserType'] == 'business') {
				$_SESSION['user'] = $logged_in_user;
				header("location: ../wrxvm3_priv/business_manager.php");
			}
			// this is for the sales manager
			elseif ($logged_in_user['UserType'] == 'sales') {
				$_SESSION['user'] = $logged_in_user;
				header("location: ../wrxvm3_priv/sales_manager.php");
			}
			// this is for customers
			elseif ($logged_in_user['UserType'] == 'customer') {
				$_SESSION['user'] = $logged_in_user;
				header("location: ../wrxvm3_priv/customer_dashboard.php");
			}
		// if no user is found or password doesnt match
		} else {
			echo "<h4>(Error): User Not Found or Incorrect Password.</h4>";
			session_destroy();
		}
	}
}

// check if the user is logged in so that it protects the page if user isn't
function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	} else {
		return false;
	}
}

// destroy and unset session if user clicks log out, then send back to login page
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

?>