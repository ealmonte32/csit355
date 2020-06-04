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

?>

<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Checkout</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body bgcolor="#1e2e47">

<br>
<h3><?php echo $_SESSION['fname']; ?>, please review shopping cart before placing order:</h3>
<div style="width: 80%">

<form method="post" action="process_order.php">

<table style="border:2px solid black; background-color:#0c2438">
    <tr style="background-color:#0c2438">
    <th>Product Name</th>
    <th>Product Number</th>
    <th align="center">Quantity</th>
    <th align="center">Price</th>
    <th align="center">Total</th>
    </tr>
    <?php
    if(!empty($_SESSION["shopping_cart"]))
    {
        $details = "";
        $total = 0;
        $totalquantity = 0;
        foreach($_SESSION["shopping_cart"] as $keys => $values)
        {
    ?>
    <tr style="background-color:#1f5e66">
    <td><?php echo $values["product_name"]; ?></td>
    <td><?php echo $values["product_number"]; ?></td>
    <td align="center"><?php echo $values["product_quantity"]; ?></td>
    <td align="center">$ <?php echo $values["product_price"]; ?></td>
    <td align="center">$ <?php echo number_format($values["product_quantity"] * $values["product_price"], 2); ?></td>
    </tr>
    <?php
        $total = $total + ($values["product_quantity"] * $values["product_price"]);
        $totalquantity = $totalquantity + ($values["product_quantity"]);
        $details = $details . $values["product_quantity"] . " of Product Number " . $values["product_number"] . "<br>";
        $orderdetails = $details;
        }
    ?>
    <tr>
    <td style="border-top: 1px solid; border-right: 0px; padding-top: 10px" colspan="4" align="right">Order Total (Shipping included):</td>
    <td style="border-top: 1px solid; border-left: 0px; padding-top: 10px" align="center">$ <?php echo number_format($total, 2); ?></td>

    <input type="hidden" name="hidden_product_number" value="<?php echo $values["product_number"]; ?>" />
    <input type="hidden" name="hidden_product_quantity" value="<?php echo $values["product_quantity"]; ?>" />
    <input type="hidden" name="hidden_total_quantity" value="<?php echo "$totalquantity"; ?>" />
    <input type="hidden" name="hidden_customerid" value="<?php echo $_SESSION['customerid']; ?>" />
    <input type="hidden" name="hidden_order_total" value="<?php echo $total; ?>" />
    <input type="hidden" name="hidden_order_details" value="<?php print $orderdetails; ?>" />

    <?php
    }
    ?>
</tr>
</table><br>
<input type="submit" name="process_order" value="Place Order">
</form></div>

<?php
/*
$details = "";
$orderdetails = "";
foreach($_SESSION["shopping_cart"] as $keys => $values)
    {
        $details = $details . $values["product_quantity"] . " of Product Number " . $values["product_number"] . "<br>";
    }
$orderdetails = $details;
*/
?>

<div>
<p>
<form method="post" action="customer_dashboard.php">
<input type="submit" name="returntocustomerdashboard" value="Return to Customer Dashboard">
</form>
</p>
</div>

<hr style="border: 1px dotted white;" />

<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>
