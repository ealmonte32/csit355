<?php
require_once('../wrxvm3_priv/database.php');

// Get the user data from customer profile form
$var_currentpassword = filter_input(INPUT_POST, 'currentpassword');
$hashed_var_currentpassword = sha1(sha1($var_currentpassword)); //double hash the password entered

$var_newpassword = filter_input(INPUT_POST, 'newpassword');
$hashed_var_newpassword = sha1(sha1($var_newpassword)); //double hash the new password

$var_confirmnewpassword = filter_input(INPUT_POST, 'confirmnewpassword');
$hashed_var_confirmnewpassword = sha1(sha1($var_confirmnewpassword)); //double hash the new password

// check if both entries of the new password matches
if ($var_newpassword != $var_confirmnewpassword) {
    echo "<script>alert('(Error) New password and confirmation must match, please try again.'); window.location.href='customer_profile.php#changepassword';</script>";
    exit;
}

$var_customerid = filter_input(INPUT_POST, 'customerid');

//query the database for current password
$getpass = $db->query("SELECT Password FROM almontee3_CUSTOMERS WHERE CustomerID='$var_customerid'");
$currentdbpass = $getpass->fetch();

foreach ($currentdbpass as $result) {

    // check if the current database password matches the entered password
    if ($result == $hashed_var_currentpassword) {

    $query = "UPDATE almontee3_CUSTOMERS SET Password=:hashed_var_newpassword WHERE CustomerID=:var_customerid";
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':hashed_var_newpassword', $hashed_var_newpassword);
    $statement->bindValue(':var_customerid', $var_customerid);
    $statement->execute();
    $statement->closeCursor();
    echo "<script>alert('Password updated successfully, you will now be logged out.'); window.location.href='login.php?logout=true';</script>";
    }
    else {
        echo "<script>alert('(Error) The current password you entered does not match, please try again.'); window.location.href='customer_profile.php#changepassword';</script>";
        exit;
    }
}

?>