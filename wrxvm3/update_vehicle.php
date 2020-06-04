<?php
require_once('../wrxvm3_priv/database.php');

// Get the user data from customer profile form
$var_vehiclemodel = filter_input(INPUT_POST, 'vehiclemodel');
$var_vehicleyear = filter_input(INPUT_POST, 'vehicleyear');
$var_customerid = filter_input(INPUT_POST, 'customerid');

// add the customer to database
$query = "UPDATE almontee3_CUSTOMERS SET VehicleModel=:var_vehiclemodel, VehicleYear=:var_vehicleyear WHERE CustomerID=:var_customerid";
$statement = $db->prepare($query) or die ("Failed to prepare statement!");
$statement->bindValue(':var_vehiclemodel', $var_vehiclemodel);
$statement->bindValue(':var_vehicleyear', $var_vehicleyear);
$statement->bindValue(':var_customerid', $var_customerid);
$statement->execute();
$statement->closeCursor();

//we need to query the database now after it was updated so that the account information shows latest info
//and we then need to send the latest info to the current session
$getinfo = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE CustomerID='$var_customerid'");
$results = $getinfo->fetchAll();

foreach ($results as $user){
        $_SESSION['user'] = $user;
        $_SESSION['vehiclemodel'] = $user['VehicleModel'];
        $_SESSION['vehicleyear'] = $user['VehicleYear'];
    }

echo "<script>alert('Vehicle info updated successfully.'); window.location.href='customer_profile.php';</script>";

?>