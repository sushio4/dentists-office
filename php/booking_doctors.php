<!DOCTYPE html>
<html>
<?php
	session_start();

	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
		header("location: index.php");
		exit;
	}    
	
	if(!isset($_GET["service_id"])) {
        echo "<h2>Nie wybrano usługi! Proszę wrócić</h2>";
		exit;
    }
    if(!isset($_GET["time"])) {
        echo "<h2>Nie wybrano terminu! Proszę wrócić</h2>";
		exit;
    }

?>
<head>
	<title>Rezerwacja wizyty</title>
	<style>
		body {
			background-color: white;
		}
		header {
			background-color: #b0d0ff;
			display: flex;
			justify-content: space-around;
			align-items: center;
			height: 10vh;
		}
		.button {
            transform: scale(2);
		}
		#main_div {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            align-items: stretch;
		}
		#content {
			background-color: #e0f0ff;
			flex-grow: 1;
		}
		table {
			border-collapse: collapse;
			transform: scale(1.3);
		}
		table, th, td {
			border: 2px ridge black;
			background-color: #d0f0ff;
		}
		#timetable {
			margin-top: 50px;
			margin-bottom: 20px;
			max-width: fit-content;
			margin-inline: auto;
		}
		#table_header {
			max-width: fit-content;
			margin-inline: auto;
		}
		#submit_button {
			max-width: fit-content;
			margin-inline: auto;
		}
	</style>
	<meta charset="utf-8">
	<script>
		const service_id = <?php echo $_GET["service_id"]; ?>;
		const time = "<?php echo $_GET["time"]; ?>";
	</script>
	<script src="/booking_doctors.js"></script>
</head>
<body>
	<header>
		<div id="profile">
			<form action="booking_time.php">
				<input type="submit" value="Powrót" class="button">
			</form>
		</div>
		
		<h1>Ząbex - Klinika Dentystyczna</h1>

		<div id="logout">
			<form action="logout.php">
				<input type="submit" value="Wyloguj" class="button">
			</form>
		</div>
	</header>
	<div id="main_div">
		<div id="content">
			<div id="table_header">
				<h2>Wybierz lekarza</h2>
			</div>
			<form action="booked.php" method="POST">
				<div id="timetable">
				</div>
				<br>
				<div id="submit_button" class="button" style="margin-bottom: 30px; margin-top: 30px">
					<input type="submit" value="Potwierdź rezerwację">
				</div>
			</form>
		</div>
	</div>
</body>
</html>