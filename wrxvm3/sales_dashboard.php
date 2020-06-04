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
    if ($currentuser['UserType'] == 'sales')
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


//if product remove button is clicked
if(isset($_POST['removeproductid'])){ //this prevents the notice if post is currently empty
    $removeproductid = filter_input(INPUT_POST, 'removeproductid', FILTER_SANITIZE_NUMBER_INT);
    $query = $db->prepare("DELETE FROM almontee3_PRODUCTS WHERE ProductID='$removeproductid'");
    $statement = $query->execute();
    echo "<script>alert('Product removed!'); window.location.href='sales_dashboard.php';</script>";
}

//if remove inquiry button is clicked
if(isset($_POST['removeinquiryid'])){ //this prevents the notice if post is currently empty
    $removeinquiryid = filter_input(INPUT_POST, 'removeinquiryid', FILTER_SANITIZE_NUMBER_INT);
    $query = $db->prepare("DELETE FROM almontee3_INQUIRIES WHERE InquiryID='$removeinquiryid'");
    $statement = $query->execute();
    echo "<script>alert('Inquiry removed!'); window.location.href='sales_dashboard.php#inquiries';</script>";
}

?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Sales Manager Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body bgcolor="#3d3818">
<div id="topdashboard"></div>
<div style="padding-left: 30px; padding-top: 10px; padding-right: 30px">
<img src="images/sales_user_icon.jpg" style="width:50px; height:50px; border-radius:50%"><br>
<b>Welcome, <?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?>.</b><br>
<i>May the force be with you today, and may it give you the patience to handle customers.</i><br><br>

<u style="color: orange">Employee ID: </u><br><?php echo $_SESSION['employeeid']; ?><br><br>
<u style="color: orange">Job Title: </u><br><?php echo $_SESSION['jobtitle']; ?><br><br>

<h3 style="color: orange">Actions: 
<form action="sales_dashboard.php#add_a_product">
<input type="submit" value="Add a Product">
</form>

<form action="sales_dashboard.php#inquiries">
<input type="submit" value="Inquiries">
</form>

<form action="sales_dashboard.php#orders">
<input type="submit" value="Customer Orders">
</form>

<form method="post">
<input style="color: red" type="submit" name="logout" value="Log out">
</form>
</h3>
<br>

<hr style="border: 1px dotted white;" />

<div>
<h2 align="left">Products in Inventory :<br>

<form method="get" action="sales_dashboard.php?action=showall">
<input type="submit" name="showall" value="Show All Products">
</form></h2>
</div>

Filter by Vehicle:
<form method="post" action="sales_dashboard.php?action=filterby">
<select name="vehiclemodel">
    <option value="BMW M3">BMW M3</option>
    <option value="Subaru WRX">Subaru WRX</option>
</select>
<input type="submit" name="filterby" value="Filter">
</form><br>

Filter by Category:
<form method="post" action="sales_dashboard.php?action=filterbycategory">
<select name="productcategory">
    <option value="Engine">Engine</option>
    <option value="Air Filters">Air Filters</option>
    <option value="Exhaust Systems">Exhaust Systems</option>
    <option value="Suspension">Suspension</option>
    <option value="Wheels">Wheels</option>
</select>
<input type="submit" name="filterbycategory" value="Filter">
</form><br>


<form method="post" action="sales_dashboard.php?action=searchby">
Product Description or Product Number: <br>
<input type="text" name="searchterm" size="30">
<input type="submit" name="searchby" value="Search">
</form>
<br>

<?php

//default array to show no products
$result = [];

//if condition to show all products 
if(isset($_GET['showall'])){ //this prevents the notice if GET is currently empty
$query = $db->query("SELECT * FROM almontee3_PRODUCTS ORDER BY ProductID");
$result = $query->fetchAll();
}

//if condition for filtering
if(isset($_POST['filterby'])){ //this prevents the notice if POST is currently empty
    $vehiclemodel = filter_input(INPUT_POST, 'vehiclemodel');
    $query = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE FitsVehicle='$vehiclemodel' ORDER BY ProductID");
    $result = $query->fetchAll();
}

//if condition for category filtering
if(isset($_POST['filterbycategory'])){ //this prevents the notice if POST is currently empty
    $productcategory = filter_input(INPUT_POST, 'productcategory');
    $query = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE Category='$productcategory' ORDER BY ProductID");
    $result = $query->fetchAll();
}

//if condition for search
if(isset($_POST['searchby'])){ //this prevents the notice if POST is currently empty
    $searchterm = filter_input(INPUT_POST, 'searchterm');
    $query = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE (Description LIKE '%$searchterm%') OR (ProductNumber='$searchterm') ORDER BY ProductID");
    $result = $query->fetchAll();
}

foreach ($result as $row)

{
?>

<div style="width:60%">
    <div style="border:1px solid black; padding: 10px; background-color:#0c2438;">
    <b>Current Product Image: <br></b><img src="<?php echo $row["ImageLocation"]; ?>" style="width:50px;height:50px"><p>
    <b>Product ID: </b><?php echo $row["ProductID"]; ?><br>
    <b>Product Name: </b><?php echo $row["ProductName"]; ?><br>
    <b>Product Number: </b><?php echo $row["ProductNumber"]; ?><br>
    <b>Description: </b><?php echo $row["Description"]; ?><br>
    <b>VendorID: </b><?php echo $row["VendorID"]; ?><br>
    <b>Fits Vehicle: </b><?php echo $row["FitsVehicle"]; ?><br>
    <b>In Stock: </b><?php echo $row["InStock"]; ?><br>
    <b>Current Price: $</b><?php echo $row["UnitPrice"]; ?><br>
    <b>Category: </b><?php echo $row["Category"]; ?><br>
    <form action="sales_dashboard.php?action=removeproductid" method="post">
    <input type="hidden" name="removeproductid" value="<?php echo $row['ProductID']; ?>">
    <input style="background-color: red; color: white; border-radius: 2px; padding: 3px" type="submit" value="(Warning) Remove Product from Inventory">
    </form>
    </div><br>
</p></div>

<?php
}
?>

<hr style="border: 1px dotted white;" />

<div id="add_a_product"></div>
        <h2 align="left" style="color: orange">Add a Product :</h2>
        <i><a href="sales_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>

        <table style="padding: 5px; width: 35%; background-color: #004c4f">
            <form action="add_product.php" method="post" enctype="multipart/form-data">

            <tr><td align="right"><label>Product Name: &nbsp;</label></td>
            <td><input type="text" name="productname" size="35" maxlength="32" required><br></td></tr>

            <tr><td align="right"><label>Product Number: &nbsp;</label></td>
            <td><input type="text" name="productnumber" size="35" maxlength="10" placeholder="Part Number" required><br></td></tr>

            <tr><td align="right"><label>Description: &nbsp;</label></td>
            <td><textarea name="description" rows="6" cols="37" placeholder="Describe the product..."></textarea><br></td></tr>

            <tr><td align="right"><label>Vendor ID: &nbsp;</label></td>
            <td><input type="number" name="vendorid" size="10" min="9000" max="9999" placeholder="9xxx" required><br></td></tr>

            <tr><td align="right"><label>Fits Vehicle: &nbsp;</label></td>
            <td><select name="fitsvehicle">
                <option value="BMW M3">BMW M3</option>
                <option value="Subaru WRX">Subaru WRX</option>
                </select><br></td></tr>

            <tr><td align="right"><label>In Stock: &nbsp;</label></td>
            <td><input type="number" name="instock" size="10" min="1" max="99" required><br></td></tr>

            <tr><td align="right"><label>Price: &nbsp;</label></td>
            <td><input type="number" name="price" size="10" min="1" max="9999" required><br></td></tr>

            <tr><td align="right"><label>Product Category: &nbsp;</label></td>
            <td><select name="productcategory">
                <option value="Engine">Engine</option>
                <option value="Air Filters">Air Filters</option>
                <option value="Exhaust Systems">Exhaust Systems</option>
                <option value="Suspension">Suspension</option>
                <option value="Wheels">Wheels</option>
            </select><br></td></tr>

            <tr><td align="right"><label>Select image of product: &nbsp;</label></td>
            <td><input type="file" name="imagefileforcar" id="imagefileforcar" required><br></td></tr>
        </table><br>
            <input type="submit" name="submit" value="Add Product">
        </form>

<br><br>


<hr style="border: 1px dotted white;" />

<div id="inquiries">
<h2 align="left" style="color: orange">Inquiries Management :</h2>
<i><a href="sales_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>
</div>
<br>

<?php

//query all inquiries submitted
$query = $db->query("SELECT * FROM almontee3_INQUIRIES ORDER BY InquiryID");
$inquiryresult = $query->fetchAll();

foreach ($inquiryresult as $row)

{
?>

    <div style="width:50%; border:1px solid black; padding: 5px; background-color:#014f34;">
    <br><img src="images/inquiries_icon.jpg" style="width:50px; height:50px; border-radius:50%"><p>
    <u>Inquiry ID:</u> <?php echo $row["InquiryID"]; ?><br><br>
    <u>First Name:</u> <?php echo $row["FirstName"]; ?><br>
    <u>Last Name:</u> <?php echo $row["LastName"]; ?><br>
    <u>Email:</u> <?php echo $row["Email"]; ?><br>
    <u>Telephone:</u> <?php echo $row["Telephone"]; ?><br>
    <u>Comments:</u> <?php echo $row["Comments"]; ?><br>
    <form action="sales_dashboard.php?action=removeinquiry&#inquiries" method="post">
    <input type="hidden" name="removeinquiryid" value="<?php echo $row['InquiryID']; ?>">
    <input style="background-color: white; color: black; border-radius: 2px; font-weight: bold" type="submit" value="Remove Inquiry">
    </form>
    </div><br>

<?php
}
?>



<hr style="border: 1px dotted white;" />

<div id="orders">
<h2 align="left" style="color: orange">Orders Management :</h2>
<i><a href="sales_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>
</div>


<?php

//query all customer orders submitted
$query = $db->query("SELECT * FROM almontee3_ORDERS ORDER BY OrderNumber");
$orders = $query->fetchAll();

foreach ($orders as $row)

{
?>

    <div style="width:50%; border:1px solid black; padding: 5px; background-color:#004c4f;">
    <br><img src="images/orders_icon.jpg" style="width:50px; height:50px; border-radius:50%"><p>
    <u>Order Number:</u> <?php echo $row["OrderNumber"]; ?><br><br>
    <u>Ordered by:</u> <?php echo $row["OrderedBy"]; ?><br>
    <u>Order date:</u> <?php echo $row["OrderDate"]; ?><br>
    <u>Order details:</u> <br><?php echo $row["OrderDetails"]; ?><br><br>
    <u>Sales total:</u> $<?php echo number_format($row["TotalPrice"], 2); ?><br>
    <u>Order quantity:</u> <?php echo $row["OrderQuantity"]; ?><br>
    <u>Shipped date:</u> <?php echo $row["ShippedDate"]; ?><br>
    <u>Shipping cost:</u> <?php echo $row["ShippingCost"]; ?><br>
    <u>Sales discount:</u> <?php echo $row["Discount"]; ?><br>
    <u>Status:</u> <?php echo $row["Status"]; ?><br><br>

    Set Order Status:
    <form action="update_order_status.php" method="post">
    <input type="hidden" name="orderid" value="<?php echo $row['OrderNumber']; ?>">
    <input type="hidden" name="orderstatus" value="Order processed successfully.">
    <input style="background-color: #239450; color: black; border-radius: 2px; width: 25%; font-weight: bold" type="submit" value="Order Processed">
    </form>

    <form action="update_order_status.php" method="post">
    <input type="hidden" name="orderid" value="<?php echo $row['OrderNumber']; ?>">
    <input type="hidden" name="orderstatus" value="Order still pending.">
    <input style="background-color: #ff8400; color: black; border-radius: 2px; width: 25%; font-weight: bold" type="submit" value="Order Pending">
    </form>

    <form action="update_order_shipped.php" method="post">
    <input type="hidden" name="orderid" value="<?php echo $row['OrderNumber']; ?>">
    <input type="hidden" name="ordershipped" value="<?php echo date('Y-m-d'); ?>">
    <input style="background-color: #00b39e; color: black; border-radius: 2px; width: 25%; font-weight: bold" type="submit" value="Order Shipped">
    </form>
    </div><br>

<?php
}
?>





<i><a href="sales_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a></i>
<hr style="border: 1px dotted white;" />
</p></div>
<br><br>
<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>