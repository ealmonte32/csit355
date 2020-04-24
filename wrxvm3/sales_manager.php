<?php
require_once('../wrxvm3_priv/database.php');
session_start();

// send user back to login if current page is not being accessed under an administrative user
if (!isAdmin()) {
    echo "You must be an admin";
    header("location: logintest.php");
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
}

// this checks if user is in administrative mode
function isAdmin(){
    $user = $_SESSION['user'];
    if ($user['UserType']=="sales") {
        return true;
    }
    else {
        return false;  
    }
}

//fetch all products from table
$query = 'SELECT * FROM almontee3_PRODUCTS ORDER BY ProductID';
$statement = $db->prepare($query);
$statement->execute();
$inventory = $statement->fetchAll();
$statement->closeCursor();

//fetch all customer inquiries
$query = 'SELECT * FROM almontee3_INQUIRIES ORDER BY InquiryID';
$statement = $db->prepare($query);
$statement->execute();
$inquiries = $statement->fetchAll();
$statement->closeCursor();

?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Sales Manager Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body bgcolor="#1e2e47">
<center><header><h1>Sales Manager Dashboard</h1></header></center>
<br>
<b><a href="login.php?logout='1'" style="color:red;">Log out</a></b>

<hr style="border: 1px dotted white;" />
    <section>
<!-- display a table of products inventory -->
        <table bgcolor="#5f6266">
            <h2>Current Products Inventory</h2>
            <?php foreach ($inventory as $product) : ?>
            <tr>
                <td align="center"><img src="images/<?php echo $product['car_image']; ?>" height="75" width="75"></td>
                <td>Make: <?php echo $product['Make']; ?> <br> Model: <?php echo $product['Model']; ?><br>
                    Year: <?php echo $product['Year']; ?> <br> Mileage: <?php echo $product['Mileage']; ?></td>
                <td>Price: $<?php echo $product['Price']; ?><br> Condition: <?php echo $product['Cond']; ?>
                <br>Availability: <?php echo $product['car_availability']; ?>
                <br>Vehicle ID: <?php echo $product['vehicle_id']; ?></td>
                </td>
            </tr>
            <td colspan="3" bgcolor="#1e2e47"><br></td>
            <?php endforeach; ?> 
        </table>
    </section>
<br><br>
        <h2>Update Vehicle Inventory</h2>
        <h3><i>(To update vehicle, please enter vehicle ID and make changes)</i></h3>
        <table class="tablesales">
            <form action="update_car.php" method="post">
            <tr><td><label>Enter Vehicle ID:</label></td>
            <td><input type="text" name="vehicle_id"><br></td></tr>

            <tr><td><label>Price:</label></td>
            <td><input type="text" name="carprice"><br></td></tr>

            <tr><td><label>Availability:</label></td>
            <td>
            <select name="caravailability">
            <option value="In Stock">In Stock</option>
            <option value="Sold">Sold</option>
            </select><br></td></tr>
        </table>
            <input type="submit" value="Update Car information">
        </form>
<br><br>
<hr style="border: 1px dotted white;" />

    <section>
<!-- display a table of inquiries from contact us -->
        <table bgcolor="#5f6266">
            <h2>Current Inquiries</h2>
            <?php foreach ($inquiries as $inquiry) : ?>
            <tr>
                <td><b>Inquiry ID:</b> <?php echo $inquiry['inquiry_id']; ?><br>
                <p><b>First Name:</b> <?php echo $inquiry['FirstName']; ?><br>
                        <b>Last Name:</b> <?php echo $inquiry['LastName']; ?><br>
                <b>Email:</b> <?php echo $inquiry['Email']; ?></td>
                <td><b>Inquiry from customer:</b> <p><?php echo $inquiry['inquiry']; ?></p></td>
                <td><form action="delete_inquiry.php" method="post">
                <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['inquiry_id']; ?>">
                <input type="submit" value="Delete Inquiry">
                </form></td>
            </tr>
            <td colspan="3" bgcolor="#1e2e47"><br></td>
            <?php endforeach; ?> 
        </table>
    </section>
    <br><br>

<br><br>
<hr style="border: 1px dotted white;" />

<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>