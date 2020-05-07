<?php
session_start();
require_once('../wrxvm3_priv/database.php');

//prepare the sql injections
$email_injection1 = "1' OR '1'='1";
$email_injection2 = '1" OR "1"="1';


$protected_email1= filter_input(INPUT_POST, $email_injection1); //try sql injection 1
$protected_email2= filter_input(INPUT_POST, $email_injection2); //try sql injection 2


$unprotected_email1= ($email_injection1); //try sql injection 1 with unprotected input filter
$unprotected_email2= ($email_injection2); //try sql injection 2 with unprotected input filter

// try all 4 executions to see how filters protect from sql injections
$email_check_query = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$protected_email1'");
$result1 = $email_check_query->fetchAll();

$email_check_query = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$protected_email2'");
$result2 = $email_check_query->fetchAll();

$email_check_query = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$unprotected_email1'");
$result3 = $email_check_query->fetchAll();

$email_check_query = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$unprotected_email2'");
$result4 = $email_check_query->fetchAll();

echo "This is what the attacker sees with email injection 1 and protected email:"; echo "<br>";
print_r($result1); echo "<br>";echo "<br>";

echo "This is what the attacker sees with email injection 2 and protected email:"; echo "<br>";
print_r($result2); echo "<br>";echo "<br>";

echo "This is what the attacker sees with email injection 1 and unprotected email:"; echo "<br>";
print_r($result3); echo "<br>";echo "<br>";

echo "This is what the attacker sees with email injection 2 and unprotected email:"; echo "<br>";
print_r($result4); echo "<br>";echo "<br>";


/*
	As we can see on the results page (depending which method of quotes you used on the WHERE Email= clause),
	the filter_input built-in function works to protect from sql injection similar to the way the addslashes built-in function does.
*/

session_destroy();
?>