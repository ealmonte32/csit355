<?php
//employee login
include('../wrxvm3_priv/functions_emp.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>WRX vs M3 - Employee Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body bgcolor="#0f0f0f">

<center>
<div style="background-image: url('deathstar_bg.jpg'); background-attachment: all; background-size: cover; border-radius:50%; height:300px; width:800px; padding: 10px;">

<main>
<br>
<form action="login_emp.php" method="post">
<table class="tablelogin">
<br><br><br><br><br>
<tr>
<td class="tdlogin"><b><label>Email: </label></td></b>
<td class="tdlogin"><input type="email" name="login_email" size="25" required></td>
</tr>
<tr>
<td class="tdlogin"><b><label>Password: </label></td></b>
<td class="tdlogin"><input type="password" name="login_password" size="25" required></td>
</tr>
</table>
<br>
<input type="submit" value="Login" name="emp_login_button">
<input type="reset" value="Clear">
</form>
</main>
</div>
<br>
<a href="login.php">Return to customer login page</a>

<br>
<h5>Copyright &copy; 2020 WRXvsM3 LLC</h5>
</center>
</body>
</html>