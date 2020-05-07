<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Registration</title>
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
    <a href="aboutus.php">About Us</a>
    <a href="contactus.php">Contact Us</a>
    <a href="login.php">Login</a>
</div>

<div style="padding:20px">
<img src="images/customer_register_logo.jpg" style="height:60px; width:60px; border-radius:20%">
<h3>Please complete the form below to create an account with us.</h3>

<table class="tableregister">
<form action="registration_process.php" method="post">
<tr>
<td>First Name:</td>
<td><input type="text" name="firstname" size="30" autofocus required><br></td>
</tr>
<tr>
<td>Last Name:</td>
<td><input type="text" name="lastname" size="30" required><br></td>
</tr>
<tr>
<td>E-mail:</td>
<td><input type="email" name="emailaddress" size="30" placeholder="email@example.com" required><br></td>
</tr>
<tr>
<td>Password:</td>
<td><input type="password" name="password" size="30" required><br></td>
</tr>
<tr>
<td>Telephone:</td>
<td><input type="tel" name="telephone" size="30" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Ex: 201-555-3344"><br></td>
</tr>
<tr>
<td>Shipping Address:</td>
<td><input type="text" name="shippingaddress" size="30" placeholder="1 Sample Street, ExampleCity, NJ, 00000" required><br></td>
</tr>
<tr>
<td>Date of Birth:</td>
<td><input type="date" name="dob"><br></td>
</tr>
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
<p><input type="submit" value="Submit"> <input type="reset" value="Clear"></p>
</form>
</div>
 
<br>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5>
</center>
</body>
</html>