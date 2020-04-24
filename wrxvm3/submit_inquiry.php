<?php
//connect to db
require_once('../wrxvm3_priv/database.php');

// Get the inquiry data and assign them to variables
$var_firstname = filter_input(INPUT_POST, 'firstname');
$var_lastname = filter_input(INPUT_POST, 'lastname');
$var_emailaddress = filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_EMAIL);
$var_telephone = filter_input(INPUT_POST, 'telephone');
$var_comments = filter_input(INPUT_POST, 'comment');

// Insert the inquiry into the database
   $query = 'INSERT INTO almontee3_INQUIRIES (FirstName, LastName, Email, Telephone, Comments) VALUES (:firstname, :lastname, :emailaddress, :telephone, :comment)';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':firstname', $var_firstname);
    $statement->bindValue(':lastname', $var_lastname);
    $statement->bindValue(':emailaddress', $var_emailaddress);
    $statement->bindValue(':telephone', $var_telephone);
    $statement->bindValue(':comment', $var_comments);
    $statement->execute();
    $statement->closeCursor();

    header('Location: inquirysubmitted.php');

?>