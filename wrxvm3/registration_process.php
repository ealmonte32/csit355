<?php

require_once('../wrxvm3_priv/database.php'); //connect to db

// Get the user data from registration and assign to variables
$var_firstname = filter_input(INPUT_POST, 'firstname');
$var_lastname = filter_input(INPUT_POST, 'lastname');
$var_email = filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_EMAIL);
$var_password = filter_input(INPUT_POST, 'password');
$hashedpass = sha1($var_password); //we convert the plaintext password into a sha1 hash
$var_telephone = filter_input(INPUT_POST, 'telephone');
$var_shippingaddress = filter_input(INPUT_POST, 'shippingaddress');
$var_dob = filter_input(INPUT_POST, 'dob');
$var_vehiclemodel = filter_input(INPUT_POST, 'vehiclemodel');
$var_vehicleyear = filter_input(INPUT_POST, 'vehicleyear', FILTER_SANITIZE_NUMBER_INT);

/*
// this functions checks if the email address entered by user already exists on the customers table
function checkEmailExist() {

//make global of var_email to access outside of function
global $var_email, $db;


// connection info
$dsn = 'mysql:host=localhost;dbname=wrxvm3';
$user = 'root';
$pass = '';


//$tempconnect = @ new PDO($dsn, $user, $pass);

// now that we opened a temporary connection to database, run the query on the customer table
$email_check_query = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$var_email'");//" LIMIT 1");
$result = $email_check_query->fetch();

echo $result;

// if the email already exists on customers table - return false
if ($result['Email'] == $var_email) {
    return false;
    //$tempconnect = null; // close the connection
    }

//function close
}
*/

//query the database table
$email_check_query = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$var_email'");//" LIMIT 1");
$result = $email_check_query->fetchAll();

// Validate inputs make sure not null/empty - we could have also used empty($var) but i like it this way
if (empty($var_firstname) || empty($var_lastname) || empty($var_email) || empty($var_password) || empty($var_shippingaddress) || empty($var_vehiclemodel) || empty($var_vehicleyear)) {
    echo "Error: Some fields cannot be left empty. Check all fields and try again.";
    //include('error.php');

    }
    // check if email exists on database table
    elseif ($result['Email'] == $var_email) {
        echo "Error: That E-mail address already exists.";
        //include('error.php');
        }
else {

    // add the customer to database
    $query = 'INSERT INTO almontee3_CUSTOMERS (FirstName, LastName, Email, Telephone, Password, VehicleModel, VehicleYear, Address, UserType, DOB) VALUES (:firstname, :lastname, :email, :telephone, :hashedpass, :vehiclemodel, :vehicleyear, :address, :usertype, :dob)';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':firstname', $var_firstname);
    $statement->bindValue(':lastname', $var_lastname);
    $statement->bindValue(':email', $var_email);
    $statement->bindValue(':telephone', $var_telephone);
    $statement->bindValue(':hashedpass', $hashedpass);
    $statement->bindValue(':vehiclemodel', $var_vehiclemodel);
    $statement->bindValue(':vehicleyear', $var_vehicleyear);
    $statement->bindValue(':address', $var_shippingaddress);
    $statement->bindValue(':usertype', 'customer');
    $statement->bindValue(':dob', $var_dob);
    $statement->execute();
    $statement->closeCursor();

    header('Location: customer_registered.php');
    
    }

?>