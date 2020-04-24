<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Contact Us</title>
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
    <a class="active" href="contactus.php">Contact Us</a>
    <a href="login.php">Login</a>
</div>

<div style="padding:20px">
<img src="images/contactus_icon.jpg" height="60px" width="60px">
<h3>Need help? Please fill out the form below with your inquiry:</h3>

<form action="submit_inquiry.php" method="post">
<table class="tablecontactus">
<tr>
<td>First Name:</td>
<td><input type="text" name="firstname" size="35" autofocus required><br></td>
</tr>
<tr>
<td>Last Name:</td>
<td><input type="text" name="lastname" size="35" required><br></td>
</tr>
<tr>
<td>E-mail:</td>
<td><input type="email" name="emailaddress" size="35" placeholder="email@example.com" required><br></td>
</tr>
<tr>
<td>Telephone:</td>
<td><input type="tel" name="telephone" size="35" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Ex: 201-555-3344"><br></td>
</tr>
<tr>
<td>Comments:</td>
<td>
<textarea name="comment" rows="10" cols="40" placeholder="Tells us how we can help..."></textarea>
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