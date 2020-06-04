<?php
session_start();
require_once('../wrxvm3_priv/database.php');

// get the data

$total_quantity = filter_input(INPUT_POST, 'hidden_total_quantity', FILTER_SANITIZE_NUMBER_INT);
$order_total = filter_input(INPUT_POST, 'hidden_order_total', FILTER_SANITIZE_NUMBER_INT);
$orderby = filter_input(INPUT_POST, 'hidden_customerid', FILTER_SANITIZE_NUMBER_INT);
$orderdate = date('Y-m-d');

$orderdetails = filter_input(INPUT_POST, 'hidden_order_details');

    // add order to database
    $query = 'INSERT INTO almontee3_ORDERS (OrderQuantity, OrderedBy, OrderDetails, OrderDate, ShippingCost, TotalPrice, Status) VALUES (:total_quantity, :orderby, :orderdetails, :orderdate, 25, :order_total, "Order Pending")';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':total_quantity', $total_quantity);
    $statement->bindValue(':orderby', $orderby);
    $statement->bindValue(':orderdetails', $orderdetails);
    $statement->bindValue(':orderdate', $orderdate);
    $statement->bindValue(':order_total', $order_total);
    $statement->execute();
    $statement->closeCursor();

    //empty shopping cart after order processed
    unset($_SESSION["shopping_cart"]);
    echo "<script>alert('Order placed successfully!'); window.location.href='customer_dashboard.php';</script>";

?>