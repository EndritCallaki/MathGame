<html>
<head>
		<title>Login</title>
    <link href="style.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, user-scalable=no" />
	</head>
	
	<body>
		<h1>MathGame</h1>
		<h2>Login</h2>
		<br>
		
		<div id="login">
			<form method="post">
			<table>
				<tr>
					<td>Username: </td><td><input name="user" type="text"></td>
				</tr>
				<tr>
					<td>Passwort: </td><td><input name="pass" type="password"></td>
				</tr>
				<tr>
					<td><input type="submit" value="Login"><td>
				</tr>
			</table>
			</form>
		</div>
		
		<br><br>
		<h2>Registrierung</h2>
		<br>
		<div id="login">
			<form method="post">
			<table>
				<tr>
					<td>Username: </td><td><input name="RegUser" type="text"></td>
				</tr>
				<tr>
					<td>Passwort: </td><td><input name="RegPass" type="password"></td>
				</tr>
				<tr>
					<td><input type="submit" value="Registrieren"><td>
				</tr>
			</table>
			</form>
		</div>
		
	</body>
<?php
	session_start();
	if(isset($_POST["user"]) && isset($_POST["pass"]))
	{
	$user = $_POST["user"];
	$pass = $_POST["pass"];

	
	// Create connection
	$conn = new mysqli("10.35.47.106:3306", "k46584_erjon", "loni2006", "k46584_mathdb");

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	//echo "Bros Connected successfully";
	
	$sql = "SELECT U_Pass FROM user WHERE U_Name = '". $user . "' LIMIT 1";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row["U_Pass"] === $pass){
				$_SESSION["loggedin"] = $user;
				echo "Eingeloggt!";
				 header("Location: https://erjon.callaki.de/index.php");
			} else {
				echo "User oder Passwort nicht korrekt!";
			}
		}
	} else {
		echo "User oder Passwort nicht korrekt!";
	}
	$conn->close();
	
	
	} else if(isset($_POST["RegUser"]) && isset($_POST["RegPass"]))
	{
	$reguser = $_POST["RegUser"];
	$regpass = $_POST["RegPass"];
	
	$conn = new mysqli("10.35.47.106:3306", "k46584_erjon", "loni2006", "k46584_mathdb");
		
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$sql = "INSERT INTO user(U_Name, U_Pass) VALUES('". $reguser . "', '". $regpass ."')";
	if ($conn->query($sql) === $user) {
		echo "Dein User wurde angelegt.";
	} else {
		echo "Fehler: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
	
	} else {
		echo "<span id='notice'> Bitte gebe deinen Usernamen und dein Passwort ein oder registriere dich.</span><br><br>";
	}
?>
</html>