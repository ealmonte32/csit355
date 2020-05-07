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
<h3>Please review shopping cart before placing order:</h3>
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
    if(!empty($_SESSION["shopping_cart"]))
    {
        $total = 0;
        foreach($_SESSION["shopping_cart"] as $keys => $values)
        {
    ?>
    <tr style="background-color:#1f5e66">
    <td><?php echo $values["product_name"]; ?></td>
    <td><?php echo $values["product_number"]; ?></td>
    <td align="center"><?php echo $values["product_quantity"]; ?></td>
    <td align="center">$ <?php echo $values["product_price"]; ?></td>
    <td align="center">$ <?php echo number_format($values["product_quantity"] * $values["product_price"], 2); ?></td>
    <td align="center"><a href="customer_dashboard.php?action=delete&ProductID=<?php echo $values["product_id"]; ?>">Remove</a></td>
    </tr>
    <?php
        $total = $total + ($values["product_quantity"] * $values["product_price"]);
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

<div>
<p>
<form method="post" action="process_order.php">
<input type="submit" name="process_order" value="Place Order">
</form>
</p>
</div>

<div>
<p>
<form method="post" action="customer_dashboard.php">
<input type="submit" name="returntocustomerdashboard" value="Return to Customer Dashboard">
</form>
</p>
</div>

<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>
