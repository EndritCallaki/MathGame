
<?php
	session_start();
	$user = $_SESSION["loggedin"];
	$points = $_POST["points"];

	$conn = new mysqli("10.35.47.106:3306", "k46584_erjon", "loni2006", "k46584_mathdb");
		
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = "UPDATE user SET U_HS = '". $points ."' WHERE U_Name='". $user ."'";
	if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully" . $user . "  " . $points;
	} else {
		echo "Error updating record: " . $user . " -  " . $conn->error;
	}
	
	$conn->close();

?>