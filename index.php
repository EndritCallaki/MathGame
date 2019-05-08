<?php

session_start();

if(isset($_SESSION["loggedin"])){
} else {
    header("Location: https://erjon.callaki.de/login.php");
}
?>

<html>
	<head>
		<title>MathGame</title>
    	<link href="style.css" rel="stylesheet" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<meta name="viewport" content="width=device-width, user-scalable=no" />
	</head>
	
	<body>
	<button id="logOut" onclick="logOut()">Ausloggen</button>
		<script>
			var sol;
			var timer; 
			var points = 0;
			var counter = 70;
			var max = 0;
			
			var update = false;
			
			$(function(){
				createCalc();
				$("#time").html(counter);
				max = $("#streak").text();
				$("#streak").html(max);
				console.log("Max: " + max);
			});
			
			
			function logOut(){
				window.location.href = "https://erjon.callaki.de/logout.php";
			}
			
			function checkSol(){
				
				if($("#solution").val() == sol){
					$("#status").html("Richtig!");
					counter+=3;
					points++;
					if(points > max){
						max = points;
						update = true;
					}
					
				} else{
						$("#status").html("Falsch!");
						points = 0;
					}
				$("#solution").val("");
				createCalc();
				$("#streak").html(max);
			}
			
			function createCalc(){
				var z1 = Math.round(Math.random() * 1000);
				var z2 = Math.round(Math.random() * 1000);
				sol = z1 + z2;
				$("#calc").html(z1 + " + " + z2 + " = ");
			}
			function playGame(){
				$("#calc").attr("hidden",false);
				$("#solution").attr("hidden",false);
				$("#status").attr("hidden",false);
				$("#checkSol").attr("hidden",false);
				$("#playGame").attr("hidden",true);
				timer = setInterval(timerTick, 1000);
			}
		
			function timerTick(){
				counter--;
				if(counter == 0){
					$("#time").html(counter);
					clearInterval(timer);
					$("#calc").attr("hidden",true);
					$("#solution").attr("hidden",true);
					$("#status").attr("hidden",true);
					$("#checkSol").attr("hidden",true);
					$("#playGame").attr("hidden",false);
					counter = 70;
					points = 0;
					
					createCalc();
					$("#solution").val("");
					
					if(update){
						
						$.ajax({
						  type: "POST",
						  url: "update.php",
						  data: {points: max},
						  success: function(data){
							console.log(data);
    }
						});
						update = false;
						console.log("Max points end: " + max);
					}
				}
				else {
					$("#time").html(counter);
				}
			}
			
		</script>
		<h1>MathGame</h1>
		<div id="game">
			<button id="playGame" onclick="playGame()">Start</button>
			<span id="calc" hidden="true"></span>
			
			<input id="solution" type="text" hidden="true">
			<span id="status" hidden="true"></span>
			
			<br><br>
			<button id="checkSol" onclick="checkSol()" hidden="true">Eingabe</button>
			
			<br><br> Zeit: <span id="time">60</span>s<br><br>
			<!--Max Streak: <span id="streak"></span-->
			<br><br>
			<h3>Willkommen beim MathGame!</h3>
			Drücke auf Start, du hast 60 Sekunden so viele richtige Rechnungen hintereinander zu machen wie möglich.<br> Bei einer falschen Angabe wird dein Punktestand auf 0 gesetzt. <br>Unten wird deine Streak angezeigt. Sobald du eine Rechnung richtig rechnest erhälst du einen 3 Sekunden Bonus.
		</div>
		
	</body>
</html>

<?php 

	$user = $_SESSION["loggedin"];	
	$conn = new mysqli("10.35.47.106:3306", "k46584_erjon", "loni2006", "k46584_mathdb");
		
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$sql = "SELECT U_HS FROM user WHERE U_Name = '". $user ."' LIMIT 1";
	$result = $conn->query($sql);
	$points = 0;


	
	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
				$points = $row["U_HS"];
		}
	
	}
	else {
		echo "<br><br>not found";
	}
	$conn->close();
		
	echo "<br><br><b>Max Streak:</b> <span id='streak' value='". $points ."'>". $points ."</span>";

	


?>
