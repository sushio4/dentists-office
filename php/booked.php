<!DOCTYPE html>
<html>
<?php
	session_start();

	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
		header("location: index.php");
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
</head>
<body>
	<header>
		<div id="profile">
			<form action="welcome.php">
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
	<div id="main_div">
		<div id="content">
			<div id="table_header">
			</div>
			<div id="timetable">
				<?php
					require_once "config.php";

					if($_SERVER["REQUEST_METHOD"] != "POST") {
						echo "Jak tu się znaleźliśmy?";
						return;
					}

					if(!isset($_POST["service_id"])) {
						echo "<h2>Nie wybrano usługi! Proszę wrócić</h2>";
						return;
					}
					if(!isset($_POST["time"])) {
						echo "<h2>Nie wybrano terminu! Proszę wrócić</h2>";
						return;
					}
					if(!isset($_POST["doctor"])) {
						echo "<h2>Nie wybrano terminu! Proszę wrócić</h2>";
						return;
					}

					$stmt = $db->prepare("INSERT INTO Appointments (PatientID, StaffID, ServiceID, AppointmentDate) VALUES (?, ?, ?, ?)");
					$stmt->bind_param("iiis", $_SESSION["id"], $_POST["doctor"], $_POST["service_id"], $_POST["time"]);
					if(!$stmt->execute()) {
						echo "<h1>Coś poszło nie tak :/</h1>";
					}
					else {
						echo "<h1>Zarejestrowano pomyślnie!</h1>";
					}
						
				?>
			</div>
		</div>
	</div>
</body>
</html>