<?php
require('../wrxvm3_priv/database.php');

//fetch all products from database
$query = 'SELECT * FROM almontee3_PRODUCTS ORDER BY ProductID';
$statement = $db->prepare($query);
$statement->execute();
$inventory = $statement->fetchAll();
$statement->closeCursor();


?>
<!DOCTYPE html>
<html>
<head>
<title>EKS Auto</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body bgcolor="#1e2e47">
<center>
<div>
<img src="logo_top.jpg" class="logo">
</div>
<div class="header">
    <a href=".">Home</a>
    <a class="active" href="inventory.php">Inventory</a>
    <a href="applyforloan.php">Loan Application</a>
    <a href="about.html">About Us</a>
    <a href="contactus.php">Contact Us</a>
	<a href="login.php">Dealer Login</a>
</div>

<div style="padding:20px">

<section>
        <!-- display a table of cars inventory-->
        <table bgcolor="#5f6266">
            <?php foreach ($inventory as $cars) : ?>
            <tr>
				<td align="center"><img src="images/<?php echo $cars['car_image']; ?>" height="175" width="300" class="tdimg"></td>
                <td>Make: <?php echo $cars['Make']; ?> <br> Model: <?php echo $cars['Model']; ?><br>
                    Year: <?php echo $cars['Year']; ?> <br> Mileage: <?php echo $cars['Mileage']; ?></td>
                <td>Price: $<?php echo $cars['Price']; ?><br> Condition: <?php echo $cars['Cond']; ?>
                <br>Availability: <?php echo $cars['car_availability']; ?>
                <br>Vehicle ID: <?php echo $cars['vehicle_id']; ?></td>
            </tr>
			<td colspan="3" bgcolor="#1e2e47"><br></td>
            <?php endforeach; ?> 
        </table>
    </section>
 </div>

<br>
<h5>Copyright &copy; 2019 EKS Auto</h5>
</center>
</body>
</html>