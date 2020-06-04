<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");

session_cache_limiter('nocache');
session_start();
require_once('../wrxvm3_priv/database.php');
require_once('error_handling.php');

// dual check if the user is not logged in, send back to login page
if(!isset($_SESSION['user'])) {
    header("location: login.php");
    exit;
}

if (!isLoggedIn()) {
    header("location: login.php");
    exit;
}

// function that checks current user session
function isLoggedIn() {
    $currentuser = $_SESSION['user'];
    if ($currentuser['UserType'] == 'customer')
        return true;
    else
        return false;
}

// assign the current customer ID from the session to the variable in order to use it on query
$customerid = $_SESSION['customerid'];

?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Customer Order History</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body bgcolor="#1e2e47">
<div id="orders">
<br>
<form action="customer_dashboard.php">
<input type="submit" value="Back to Customer Dashboard">
</form>


<hr style="border: 1px dotted orange;" />

<h2 align="left" style="color: orange">My Orders :</h2>
</div>

<?php

//query all orders for viewing history
$query = $db->query("SELECT * FROM almontee3_ORDERS WHERE OrderedBy='$customerid' ORDER BY OrderNumber");
$allorders = $query->fetchAll();

foreach ($allorders as $row)
{

?>
    <div style="width:50%; border:1px solid black; padding: 5px; background-color:#004c4f;">
    <u>Order Number:</u> <?php echo $row["OrderNumber"]; ?><br><br>
    <u>Order date:</u> <?php echo $row["OrderDate"]; ?><br>
    <u>Order details:</u> <br><?php echo $row["OrderDetails"]; ?><br><br><br>
    <u>Order total:</u> $<?php echo $row["TotalPrice"]; ?><br>
    <u>Order quantity:</u> <?php echo $row["OrderQuantity"]; ?><br>
    <u>Shipped date:</u> <?php echo $row["ShippedDate"]; ?><br>
    <u>Status:</u> <?php echo $row["Status"]; ?><br><br>
    </div><br>

<?php
}
?>

<i><a href="customer_order_history.php#orders" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>

<hr style="border: 1px dotted green;" />
<br><br>
<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>