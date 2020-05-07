<?php
// Get the car data to update
$vehicle_id = filter_input(INPUT_POST, 'vehicle_id');
$carprice = filter_input(INPUT_POST, 'carprice');
$car_availability = filter_input(INPUT_POST, 'caravailability');

// Validate inputs
if ($vehicle_id == null) {
    $error = "Invalid data. Check all fields and try again.";
    include('error.php');
} else {
    require_once('database.php');

// update the car info to database
   $query = 'UPDATE inventory SET Price=:carprice, car_availability=:caravailability WHERE vehicle_id=:vehicle_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':carprice', $carprice);
    $statement->bindValue(':caravailability', $car_availability);
    $statement->bindValue(':vehicle_id', $vehicle_id);
    $statement->execute();
    $statement->closeCursor();

    header('Location: salesperson.php');
}
?>