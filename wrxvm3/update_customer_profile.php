<?php
require_once('../wrxvm3_priv/database.php');

// Get the user data from customer profile form
$var_firstname = filter_input(INPUT_POST, 'firstname');
$var_lastname = filter_input(INPUT_POST, 'lastname');
$var_telephone = filter_input(INPUT_POST, 'telephone');
$var_shippingaddress = filter_input(INPUT_POST, 'shippingaddress');
$var_customerid = filter_input(INPUT_POST, 'customerid');

// add the customer to database
$query = "UPDATE almontee3_CUSTOMERS SET FirstName=:var_firstname, LastName=:var_lastname, Telephone=:var_telephone, Address=:var_shippingaddress WHERE CustomerID=:var_customerid";
$statement = $db->prepare($query) or die ("Failed to prepare statement!");
$statement->bindValue(':var_firstname', $var_firstname);
$statement->bindValue(':var_lastname', $var_lastname);
$statement->bindValue(':var_telephone', $var_telephone);
$statement->bindValue(':var_shippingaddress', $var_shippingaddress);
$statement->bindValue(':var_customerid', $var_customerid);
$statement->execute();
$statement->closeCursor();

//we need to query the database now after it was updated so that the account information shows latest info
//and we then need to send the latest info to the current session
$getinfo = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE CustomerID='$var_customerid'");
$results = $getinfo->fetchAll();

foreach ($results as $user){
        $_SESSION['user'] = $user;
        $_SESSION['fname'] = $user['FirstName'];
        $_SESSION['lname'] = $user['LastName'];
        $_SESSION['address'] = $user['Address'];
        $_SESSION['telephone'] = $user['Telephone'];
    }

echo "<script>alert('Info updated successfully.'); window.location.href='customer_profile.php';</script>";

?>