<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");

session_cache_limiter('nocache');
session_start();
require_once('../wrxvm3_priv/database.php');
require_once('error_handling.php');

// dual check if the user is not logged in, send back to login page
if(!isset($_SESSION['user'])) {
    header("location: login.php");
    exit;
}

if (!isLoggedIn()) {
    header("location: login.php");
    exit;
}

// function that checks current user session
function isLoggedIn() {
    $currentuser = $_SESSION['user'];
    if ($currentuser['UserType'] == 'customer')
        return true;
    else
        return false;
}

// assign the current customer ID from the session to the variable in order to use it on query
$customerid = $_SESSION['customerid'];

// we need to query the database every time the user goes into their profile page to update it
// this way any time the user updates the profile, it will show the latest info from db
$getinfo = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE CustomerID='$customerid'");
$results = $getinfo->fetchAll();

//after getting the latest info, we need to assign it to the current session or else it wont show on the customer dashboard
foreach ($results as $user){
        $_SESSION['user'] = $user;
        $_SESSION['fname'] = $user['FirstName'];
        $_SESSION['lname'] = $user['LastName'];
        $_SESSION['email'] = $user['Email'];
        $_SESSION['address'] = $user['Address'];
        $_SESSION['vehiclemodel'] = $user['VehicleModel'];
        $_SESSION['vehicleyear'] = $user['VehicleYear'];
        $_SESSION['telephone'] = $user['Telephone'];
    }

?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Customer Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body bgcolor="#1e2e47">
<br>
<hr style="border: 1px dotted orange;" />
<h3 style="color: orange">Current Account Information:</h3>

<u>Full Name:</u> <?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?><br>
<u>Email:</u> <?php echo $_SESSION['email']; ?><br>
<u>Shipping Address:</u> <?php echo $_SESSION['address']; ?><br>
<u>Current Vehicle:</u> <?php echo $_SESSION['vehicleyear'] . " " . $_SESSION['vehiclemodel']; ?><br>
<u>Telephone:</u> <?php echo $_SESSION['telephone']; ?><br>
<u>Date of birth:</u> <?php echo $_SESSION['dob']; ?><br>

<br><br>

<div>
<form action="customer_dashboard.php">
<input type="submit" value="Back to Customer Dashboard">
</form>
</div>
<br>
<hr style="border: 1px dotted orange;" />

<h3>Update My Info</h3>
<form action="update_customer_profile.php" method="post">
<table class="tableregister">
<tr>
<td>First Name:</td>
<td><input type="text" name="firstname" size="30" maxlength="32" pattern="[A-Za-z-]+" title="Letters only, no spaces" value="<?php echo $_SESSION['fname'] ?>" autofocus required><br></td>
</tr>
<tr>
<td>Last Name:</td>
<td><input type="text" name="lastname" size="30" maxlength="32" pattern="[A-Za-z-]+" title="Letters only, no spaces" value="<?php echo $_SESSION['lname'] ?>" required><br></td>
</tr>
<tr>
<td>Telephone:</td>
<td><input type="tel" name="telephone" size="30" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo $_SESSION['telephone'] ?>" required><br></td>
</tr>
<tr>
<td>Shipping Address:</td>
<td><input type="text" name="shippingaddress" size="30" maxlength="64" value="<?php echo $_SESSION['address'] ?>" required><br></td>
</tr>
</table>
<input type="hidden" name="customerid" value="<?php echo $_SESSION['customerid'] ?>" />
<p><input type="submit" value="Submit"> <input type="reset" value="Clear"></p>
</form>
<br><br>

<h3>Change My Vehicle</h3>
<form action="update_vehicle.php" method="post">
<table class="tableregister" style="width: 20%">
<tr>
<td>Vehicle Model:</td>
 <td>
<select name="vehiclemodel">
  <option value="BMW M3">BMW M3</option>
  <option value="Subaru WRX">Subaru WRX</option>
</select>
</td>
</tr>
<tr>
<td>Vehicle Year:</td>
 <td>
<select name="vehicleyear">
  <option value="2020">2020</option>
  <option value="2019">2019</option>
  <option value="2018">2018</option>
  <option value="2017">2017</option>
  <option value="2016">2016</option>
  <option value="2015">2015</option>
  <option value="2014">2014</option>
  <option value="2013">2013</option>
  <option value="2012">2012</option>
  <option value="2011">2011</option>
  <option value="2010">2010</option>
</select>
</td>
</tr>
</table>
<input type="hidden" name="customerid" value="<?php echo $_SESSION['customerid'] ?>" />
<p><input type="submit" value="Submit"> <input type="reset" value="Reset"></p>
</form>
<br><br>

<div id="changepassword"></div>
<h3>Change My Password</h3>
<i>(Upon successful change, you will be logged out)</i>
<form action="update_password.php" method="post">
<table class="tableregister">
<tr>
<td>Current Password:</td>
<td><input type="password" name="currentpassword" maxlength="64" size="30" required><br></td>
</tr>
<tr>
<td>New Password:</td>
<td><input type="password" name="newpassword" maxlength="64" size="30" required><br></td>
</tr>
<tr>
<td>Confirm New Password:</td>
<td><input type="password" name="confirmnewpassword" maxlength="64" size="30" required><br></td>
</tr>
</table>
<input type="hidden" name="customerid" value="<?php echo $_SESSION['customerid'] ?>" />
<p><input type="submit" value="Submit"> <input type="reset" value="Clear"></p>
</form>
<br><br>


<hr style="border: 1px dotted orange;" />

<footer>
<center>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5></footer>
</center>
</body>
</html>