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
				<h2>Wybierz zabieg</h2>
			</div>
			<form action="booking_time.php" method="GET">
				<div id="timetable">
					<?php
						require_once "config.php";

						// we don't need prepared statements because there is no risk of sql injection here
						$db->real_query("SELECT * FROM Services");
						$result = $db->use_result();

						$table = "<table><tr><th></th><th>Nazwa usługi</th><th>Opis</th><th>Długość</th><th>Cena</th></tr>";
						foreach($result as $row) {
							$table .= "<tr><td><input type=\"radio\" name=\"service_id\" value=\"{$row["ServiceID"]}\"</td>";
							$table .= "<td>{$row["ServiceName"]}</td><td>{$row["Description"]}</td>";

							$dur_mins = 30 * $row["DurationHalfHours"];
							$duration = date("H:i", strtotime("00:00 +{$dur_mins} minutes"));

							$table .= "<td>{$duration}</td><td>{$row["Price"]}</td></tr>";
						}
						$table .= "</table>";
						echo $table;
					?>
				</div>
				<br>
				<div id="submit_button" class="button" style="margin-bottom: 30px; margin-top: 30px">
					<input type="submit" value="Dalej">
				</div>
			</form>
		</div>
	</div>
</body>
</html>