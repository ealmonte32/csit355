<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");

session_cache_limiter('nocache');
session_start();
require_once('../wrxvm3_priv/database.php');
require_once('error_handling.php');

// dual check if the user is not logged in, send back to login_emp page
if(!isset($_SESSION['user'])) {
    header("location: login_emp.php");
    exit;
}

if (!isLoggedIn()) {
    header("location: login_emp.php");
    exit;
}

// function that checks current user session
function isLoggedIn() {
    $currentuser = $_SESSION['user'];
    if ($currentuser['UserType'] == 'business')
        return true;
    else
        return false;
}

// logout
if(isset($_POST['logout'])) {
    $_SESSION = array(); //unset all session variables
    session_unset();

    if (ini_get("session.use_cookies")) { //delete the session cookie
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
    }

    if (session_destroy()) { //destroy the session
        header("location: login_emp.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Business Manager Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<div id="topdashboard"></div>
<body bgcolor="#3d3818">

<div style="padding-left: 30px; padding-top: 10px; padding-right: 30px">
<img src="images/business_user_icon.jpg" style="width:50px; height:50px; border-radius:50%"><br>
<b>Welcome, <?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?>.</b><br>
<i>May the force be with you today, and may it help you deal with business related things.</i><br><br>

<u>Employee ID: </u><br><?php echo $_SESSION['employeeid']; ?><br><br>
<u>Job Title: </u><br><?php echo $_SESSION['jobtitle']; ?><br><br>

<h3 style="color: orange">Actions: 
<form action="business_dashboard.php#add_a_vendor">
<input type="submit" value="Add a Vendor">
</form>

<form action="business_dashboard.php#customers">
<input type="submit" value="Customer Profiles">
</form>

<form action="business_dashboard.php#orders">
<input type="submit" value="Customer Orders">
</form>

<form method="post">
<input style="color: red" type="submit" name="logout" value="Log out">
</form>
</h3>
<br>


<hr style="border: 1px dotted green;" />

<div id="add_a_vendor"></div>
        <h2 align="left" style="color: orange">Add a Vendor :</h2>
        <i><a href="business_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>

        <table style="padding: 5px; width: 35%; background-color: #003820">
            <form action="add_vendor.php" method="post" enctype="multipart/form-data">

            <tr><td align="right"><label>Vendor Name: &nbsp;</label></td>
            <td><input type="text" name="vendorname" size="35" maxlength="32" required><br></td></tr>

            <tr><td align="right"><label>Contract Number: &nbsp;</label></td>
            <td><input type="text" name="contractnumber" size="35" maxlength="10" placeholder="Ex: C0A101" required><br></td></tr>
        </table><br>
            <input type="submit" name="submit" value="Add Vendor">
        </form>

<br><br>


<hr style="border: 1px dotted green;" />

<div id="customers">
<h2 align="left" style="color: orange">Customers Overview :</h2>
<i><a href="business_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>
</div>
<br>

<?php

//query all customers for viewing info only
$query = $db->query("SELECT * FROM almontee3_CUSTOMERS ORDER BY CustomerID");
$result = $query->fetchAll();

foreach ($result as $row)

{
?>

    <div style="width:50%; border:1px solid black; padding: 5px; background-color:#014f34;">
    <br><img src="images/profile_icon.jpg" style="width:50px; height:50px; border-radius:50%"><p>
    <u>Customer ID:</u> <?php echo $row["CustomerID"]; ?><br><br>
    <u>Full Name:</u> <?php echo $row["FirstName"]." ".$row["LastName"];; ?><br>
    <u>Email:</u> <?php echo $row["Email"]; ?><br>
    <u>Vehicle:</u> <?php echo $row["VehicleYear"] . " " . $row["VehicleModel"]; ?><br>
    <u>Address:</u> <?php echo $row["Address"]; ?><br>
    <u>Date of birth:</u> <?php echo $row["DOB"]; ?><br>
    </div><br>

<?php
}
?>



<hr style="border: 1px dotted green;" />

<div id="orders">
<h2 align="left" style="color: orange">Customer Orders :</h2>
<i><a href="business_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>
</div>


<?php

//query all customer orders for viewing only
//$query = $db->query("SELECT * FROM almontee3_ORDERS ORDER BY OrderNumber");
$query = $db->query("SELECT * FROM almontee3_ORDERS JOIN almontee3_CUSTOMERS ON (OrderedBy=CustomerID)");
$orders = $query->fetchAll();

foreach ($orders as $row)

{
?>

    <div style="width:50%; border:1px solid black; padding: 5px; background-color:#004c4f;">
    <br><img src="images/orders_icon.jpg" style="width:50px; height:50px; border-radius:50%"><p>
    <u>Order Number:</u> #<?php echo $row["OrderNumber"]; ?><br><br>
    <u>Ordered by:</u> <?php echo $row["OrderedBy"] . " (" . $row["Email"] . ")"; ?><br>
    <u>Order date:</u> <?php echo $row["OrderDate"]; ?><br><br>
    <u>Order details:</u> <br><?php echo $row["OrderDetails"]; ?><br><br><br>
    <u>Sales total:</u> $<?php echo number_format($row["TotalPrice"],2); ?><br>
    <u>Order quantity:</u> <?php echo $row["OrderQuantity"]; ?><br>
    <u>Shipped date:</u> <?php echo $row["ShippedDate"]; ?><br>
    <u>Shipping cost:</u> <?php echo $row["ShippingCost"]; ?><br>
    <u>Sales discount:</u> <?php echo $row["Discount"]; ?><br>
    <u>Status:</u> <?php echo $row["Status"]; ?><br><br>
    </div><br>

<?php
}
?>



<i><a href="business_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>
<hr style="border: 1px dotted green;" />
</div>
<br><br>
<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>