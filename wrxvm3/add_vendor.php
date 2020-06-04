<?php
require_once('../wrxvm3_priv/database.php');

// Get the data from post
$vendorname = filter_input(INPUT_POST, 'vendorname');
$contractnumber = filter_input(INPUT_POST, 'contractnumber');

    // add to database
    $query = 'INSERT INTO almontee3_VENDORS (VendorName, ContractNumber) VALUES (:vendorname, :contractnumber)';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':vendorname', $vendorname);
    $statement->bindValue(':contractnumber', $contractnumber);
    $statement->execute();
    $statement->closeCursor();

    echo "<script>alert('Vendor added successfully!'); window.location.href='business_dashboard.php';</script>";

?>