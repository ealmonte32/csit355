<?php
require_once('../wrxvm3_priv/database.php');

//get data from button pressed
$var_orderid = filter_input(INPUT_POST, 'orderid');
$var_order_shipped = filter_input(INPUT_POST, 'ordershipped');

//update the database table with status selected
$query = 'UPDATE almontee3_ORDERS SET ShippedDate=:var_order_shipped WHERE OrderNumber=:var_orderid';
$statement = $db->prepare($query) or die ("Failed to prepare statement!");
$statement->bindValue(':var_orderid', $var_orderid);
$statement->bindValue(':var_order_shipped', $var_order_shipped);
$statement->execute();
$statement->closeCursor();

echo "<script>alert('Order shipping status updated successfully.'); window.location.href='sales_dashboard.php#orders';</script>";

?>