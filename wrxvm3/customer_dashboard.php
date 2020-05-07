<?php
session_start();
require_once('../wrxvm3_priv/database.php');

if (!isLoggedIn()) {
    echo "You must be logged in.";
    header("location: login.php");
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
}

function isLoggedIn() {
    $currentuser = $_SESSION['user'];
    if ($currentuser['UserType'] == 'customer')
        return true;
    else
        return false;
}

// this is for adding items to shopping cart
if (isset($_POST["add_to_cart"])) // if the add_to_cart post action was executed
{
    if (isset($_SESSION["shopping_cart"])) //check if variable named shopping_cart is set in the current session
    {
        $item_array_id = array_column($_SESSION["shopping_cart"], "product_id"); //set product_id as column key
        if (!in_array($_GET["ProductID"], $item_array_id)) //if product id is not in the array, add it
            {
            $count = count($_SESSION["shopping_cart"]); //count the number of elements in array
            $item_array = array( //grab the product info from post and add to cart
            'product_id'        => $_GET["ProductID"],
            'product_name'      => $_POST["hidden_product_name"],
            'product_number'    => $_POST["hidden_product_number"],
            'product_price'     => $_POST["hidden_product_price"],
            'product_quantity'  => $_POST["quantity"] );
            $_SESSION["shopping_cart"][$count] = $item_array; 
            echo '<script>alert("Product has been added to cart.")</script>';
            }
        else //alert if item already exists on shopping cart array
            {
            echo '<script>alert("This item is already in Shopping Cart. To change quantity, please remove it first.")</script>';
            }
        }
    else //if shopping_cart session is not set, build the item array, start the session and add the items to index 0
    {
        $item_array = array(
        'product_id'        => $_GET["ProductID"],
        'product_name'      => $_POST["hidden_product_name"],
        'product_number'    => $_POST["hidden_product_number"],
        'product_price'     => $_POST["hidden_product_price"],
        'product_quantity'  => $_POST["quantity"] );
        $_SESSION["shopping_cart"][0] = $item_array; //start in offset 0
        // test the issuing of the ++ instead of the [0]
        }
        echo '<script>alert("Product has been added to cart.")</script>';
    }


if(isset($_GET["action"])) //this prevents the notice if get action is empty
{

if ($_GET["action"] == "delete") //delete specific item
    {
    foreach($_SESSION["shopping_cart"] as $key => $value) //run the foreach loop and get shopping cart array values
        {
        if ($value['product_id'] == $_GET["ProductID"]) //if the product ID in the array matches the product ID selected to delete
            {
            unset ($_SESSION["shopping_cart"][$key]); //remove that product ID from the shopping cart array
            }
        }
        header('location: customer_dashboard.php?#shoppingcart'); //after deleting the item from cart, stay in cart section
    }


if ($_GET["action"] == "empty_cart") //empty the entire shopping cart
    {
        //clear all variables in the session
        header('location: customer_dashboard.php?#shoppingcart'); //after emptying the shopping cart, stay in section to see it cleared
        unset ($_SESSION["shopping_cart"]);
        echo '<script>alert("Shopping Cart Emptied!")</script>';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Customer Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body bgcolor="#1e2e47">

<img src="images/profile_icon.jpg" style="width:50px; height:50px; border-radius:50%"><br>
<b>Welcome, <?php echo $_SESSION['fname']; ?>.<br><br></b>
<u>Shipping Address: </u><br><?php echo $_SESSION['address']; ?><br><br>
<u>Current Vehicle: </u><br><?php echo $_SESSION['vehicleyear']; echo " "; echo $_SESSION['vehiclemodel']; ?><br><br>
<u>Telephone: </u><br><?php echo $_SESSION['telephone']; ?><br><br>

<a href="customer_profile.php" style="color:orange; text-decoration: none">Update My Info</a>
<br><br><br>


<b><a href="login.php?logout='1'" style="color:red;">Log out</a></b>
<br><br>

<hr style="border: 1px dotted white;" />

<div id="topdashboard">
<h2 align="left">Current Products Inventory :<br>
<form action="customer_dashboard.php">
<input type="submit" value="Show All Products">
</form></h2>
</div>

Filter by Vehicle:
<form method="post" action="customer_dashboard.php?action=filterby">
<select name="vehiclemodel">
    <option value="BMW M3">BMW M3</option>
    <option value="Subaru WRX">Subaru WRX</option>
</select>
<input type="submit" name="filterby" value="Filter">
</form><br>

Filter by Category:
<form method="post" action="customer_dashboard.php?action=filterbycategory">
<select name="productcategory">
    <option value="Engine">Engine</option>
    <option value="Air Filters">Air Filters</option>
    <option value="Exhaust Systems">Exhaust Systems</option>
    <option value="Suspension">Suspension</option>
    <option value="Wheels">Wheels</option>
</select>
<input type="submit" name="filterbycategory" value="Filter">
</form><br>


<form method="post" action="customer_dashboard.php?action=searchby">
Product Description or Part Number: <br>
<input type="text" name="searchterm" size="30">
<input type="submit" name="searchby" value="Search">
</form>
<br>

<?php

//default query to show all products
$query = $db->query("SELECT * FROM almontee3_PRODUCTS ORDER BY ProductID");
$result = $query->fetchAll();

//if condition for filtering
if(isset($_POST['filterby'])){ //this prevents the notice if post is currently empty
    $vehiclemodel = filter_input(INPUT_POST, 'vehiclemodel');
    $query = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE FitsVehicle='$vehiclemodel' ORDER BY ProductID");
    $result = $query->fetchAll();
}

//if condition for category filtering
if(isset($_POST['filterbycategory'])){ //this prevents the notice if post is currently empty
    $productcategory = filter_input(INPUT_POST, 'productcategory');
    $query = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE Category='$productcategory' ORDER BY ProductID");
    $result = $query->fetchAll();
}

//if condition for search
if(isset($_POST['searchby'])){ //this prevents the notice if post is currently empty
    $searchterm = filter_input(INPUT_POST, 'searchterm');
    $query = $db->query("SELECT * FROM almontee3_PRODUCTS WHERE (Description LIKE '%$searchterm%') OR (ProductNumber='$searchterm') ORDER BY ProductID");
    $result = $query->fetchAll();
}

foreach ($result as $row)
{

?>
<div style="width:50%">
<form method="post" action="customer_dashboard.php?action=add&ProductID=<?php echo $row["ProductID"]; ?>" />
    <div style="border:1px solid black; padding: 10px; background-color:#0c2438;">
    <img src="<?php echo $row["ImageLocation"]; ?>" style="width:150px;height:150px" /><p>
    <b>Product Name: </b><?php echo $row["ProductName"]; ?><br>
    <b>Product Number: </b><?php echo $row["ProductNumber"]; ?><br>
    <b>VendorID: </b><?php echo $row["VendorID"]; ?><p>
    <b>Description: </b><?php echo $row["Description"]; ?><p>
    <b>Price: $</b><?php echo $row["UnitPrice"]; ?><p>
    <input type="number" name="quantity" min="1" max="99" style="width:50px" value="1" />
    <input type="hidden" name="hidden_product_name" value="<?php echo $row["ProductName"]; ?>" />
    <input type="hidden" name="hidden_product_number" value="<?php echo $row["ProductNumber"]; ?>" />
    <input type="hidden" name="hidden_product_price" value="<?php echo $row["UnitPrice"]; ?>" />
    <input type="submit" name="add_to_cart" style="margin-top:3px;" value="Add to Cart" />
    </div><br>
</form>
</div>
<?php
}
?>
<a href="customer_dashboard.php#topdashboard" style="color:lightblue; text-decoration: none">^-Return To Top</a>

<hr style="border: 1px dotted white;" />
<div id="shoppingcart"></div>
<br>
<h3>Shopping Cart</h3>
<div style="width: 80%">
<table style="border:2px solid black; background-color:#0c2438">
    <tr style="background-color:#0c2438">
    <th>Product Name</th>
    <th>Product Number</th>
    <th align="center">Quantity</th>
    <th align="center">Price</th>
    <th align="center">Total</th>
    <th align="center">Action</th>
    </tr>
    <?php
    // if the shopping cart session is not empty, display each item on the table rows
    if(!empty($_SESSION["shopping_cart"]))
    {
        $total = 0; //we start with a total order value of 0
        foreach($_SESSION["shopping_cart"] as $key => $value) //for loop goes through each item in the shopping cart session array
        {
    ?>
    <tr style="background-color:#1f5e66">
    <td><?php echo $value["product_name"]; ?></td>
    <td><?php echo $value["product_number"]; ?></td>
    <td align="center"><?php echo $value["product_quantity"]; ?></td>
    <td align="center">$ <?php echo $value["product_price"]; ?></td>
    <td align="center">$ <?php echo number_format($value["product_quantity"] * $value["product_price"], 2); ?></td>
    <td align="center"><a href="customer_dashboard.php?action=delete&ProductID=<?php echo $value["product_id"]; ?>">Remove</a></td>
    </tr>
    <?php
        $total = $total + ($value["product_quantity"] * $value["product_price"]);
        }
    ?>
    <tr>
    <td style="border-top: 1px solid; border-right: 0px; padding-top: 10px" colspan="4" align="right">Order Total:</td>
    <td style="border-top: 1px solid; border-left: 0px; padding-top: 10px" align="center">$ <?php echo number_format($total, 2); ?></td>
    <td style="border-top: 1px solid"></td>

    <?php
    }
    ?>

</tr>
</table></div>
<br>

<div>
<p>
<form method="post" action="customer_dashboard.php?action=empty_cart">
<input type="submit" name="empty_cart" value="Empty Shopping Cart">
</form></p>
<p>
<form method="post" action="checkout.php">
<input type="submit" name="checkout" value="Checkout">
</form></p>
</div>

<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>