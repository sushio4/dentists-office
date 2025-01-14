<!DOCTYPE html>
<html>
<?php
	session_start();

	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
		header("location: index.php");
		exit;
	}

	if(!isset($_GET["service_id"])) {
		header("location: booking_visit.php");
		exit;
	}
?>
<head>
	<title>Rezerwacja wizyty</title>
	<style>
		body, html {
			height: 100vh;
		}
		header {
			background-color: #b0d0ff;
			display: flex;
			justify-content: space-around;
			align-items: center;
		}
		.button {
            transform: scale(2);
		}
		#content {
            margin-top: 10px;
			background-color: #e0f0ff;
			display: flex;
			flex-direction: column;
		}
		table {
			border-collapse: collapse;
		}
		table, th, td {
			border: 2px ridge black;
			background-color: #d0f0ff;
		}
		#table_header {
			max-width: fit-content;
			margin-inline: auto;
		}
		#timetable {
			margin-top: 30px;
			max-width: fit-content;
			margin-inline: auto;
			transform: scale(1.2);
		}
		#submit_button {
			margin-top: 60px;
			max-width: fit-content;
			margin-inline: auto;
			margin-bottom: 30px;
		}
	</style>
	<meta charset="utf-8">
	<script>
		const service_id = <?php echo $_GET["service_id"]; ?>
	</script>
	<script src="/booking_time.js"></script>
</head>
<body>
	<header>
		<div id="profile">
			<form action="booking_visit.php">
				<input type="submit" value="Powrót" class="button">
			</form>
		</div>
		
		<h1>Ząbex - Klinika Dentystyczna</h1>

		<div id=logout>
			<form action="logout.php">
				<input type="submit" value="Wyloguj" class="button">
			</form>
		</div>
	</header>
	<div id="content">
		<div id="table_header">
			<h2>Dostępne dni i godziny</h2>
		</div>
		<form action="booking_doctors.php" method="GET">
			<div id="timetable">
				<!-- JS will draw that table -->
			</div>
			<div id="submit_button" class="button">
				<input type="submit" value="Dalej">
			</div>
		</form>
	</div>
</body>
</html>