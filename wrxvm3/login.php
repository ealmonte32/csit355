<?php
//login
require_once('../wrxvm3_priv/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Login</title>
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
    <a class="active" href="login.php">Login</a>
</div>

<div style="padding:20px">

<main>
<img src="images/login_page_logo.jpg" style="height:60px; width:60px; border-radius:20%">
<br>
<br>Customer Account
<form action="login.php" method="post">
<table class="tablelogin">
<tr>
<td class="tdlogin"><label>Email: </label></td>
<td class="tdlogin"><input type="email" name="login_email" size="25" required></td>
</tr>
<tr>
<td class="tdlogin"><label>Password: </label></td>
<td class="tdlogin"><input type="password" name="login_password" size="25" required></td>
</tr>
</table>
<br>
<input type="submit" value="Login" name="login_button">
<input type="reset" value="Clear">
</form>
</main>
<br>
<p>
Don't have an account with us? Register <a href="customer_register.php" style="color: white">here</a>.
</p><br>
<a href="login_emp.php">Employee Login</a>

</div>
<br>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5>
</center>
</body>
</html>