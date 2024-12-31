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
			<form action="booking_time.php">
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
				<h2>Wybierz lekarza</h2>
			</div>
			<form action="booked.php" method="POST">
				<div id="timetable">
					<?php
						require_once "config.php";

						if(!isset($_GET["service_id"])) {
							echo "<h2>Nie wybrano usługi! Proszę wrócić</h2>";
							return;
						}
						if(!isset($_GET["time"])) {
							echo "<h2>Nie wybrano terminu! Proszę wrócić</h2>";
							return;
						}

						$visit_date = date("Y-m-d", strtotime($_GET["time"]));
						$visit_time= date("H:i", strtotime($_GET["time"]));
						$service_id = $_GET["service_id"];

						$db->real_query("SELECT StaffID, FirstName, LastName FROM Staff");
						$rows = $db->use_result();
						$staff = array();
						while($row = $rows->fetch_assoc()) {
							array_push($staff,  $row);
						}
					
						$stmt = $db->prepare("SELECT DurationHalfHours FROM Services WHERE ServiceID = ?");
						$stmt->bind_param("i", $service_id);
						$stmt->execute();
						$res = $stmt->get_result();
						$visit_duration = $res->fetch_assoc()["DurationHalfHours"];

						// we get all existing appointments after today to $res
						$date_now = date("Y-m-d H:i");
						$db->real_query("SELECT AppointmentDate, DurationHalfHours, StaffID FROM Appointments " .
										"JOIN Services USING (ServiceID) WHERE AppointmentDate >= \"{$visit_date}\"");
						$rows = $db->use_result();
						$res = array();
						while($row = $rows->fetch_assoc()) {
							array_push($res, $row);
						}

						// construct the availability table for one day to check doctors avail.
						$availability = array();
							
						$time_i = "08:00";
						// 16 half hour periods between 8 and 16
						for($j = 0; $j < 16; $j++) {
							$availability[$time_i] = [];
							foreach($staff as $s) {
								// available, we'll change that later
								$availability[$time_i][$s["StaffID"]] = true;
							}
							$time_i = date("H:i", strtotime($time_i . " +30 minutes"));
						}

						// update availability table
						foreach($res as $row) {
							$date = date("Y-m-d", strtotime($row["AppointmentDate"]));
							if($date != $visit_date) continue;

							$time = date("H:i", strtotime($row["AppointmentDate"]));
							$sid = $row["StaffID"];
							$dur = $row["DurationHalfHours"];

							for($i = 0; $i < $dur; $i++) {
								$availability[$time][$sid] = false;
								// update time for visits longer than 30min
								$time = date("H:i", strtotime($time . " +30 minutes"));
							}
						}
						
						$avail_doc = array();

						foreach($staff as $s) {
							$time_tmp = $visit_time;
							// we get availability of a doctor by looping over all the time widows the visit would take
							$avail = true;
							for($j = 0; $j < $visit_duration; $j++) {
								if(!$availability[$time_tmp][$s["StaffID"]]) {
									$avail = false;
									break;
								}
								$time_tmp = date("H:i", strtotime($time_tmp . " +30 minutes"));
							}

							if($avail) {
								array_push($avail_doc, $s);
							}
						}

						if(empty($avail_doc)) {
							echo "Invalid GET arguments you dirty hacker!";
							return;
						}

						$table = "<table>";
						foreach($avail_doc as $doc) {
							$table .= "<tr><td><input type=\"radio\" name=\"doctor\" value={$doc["StaffID"]}>{$doc["FirstName"]} {$doc["LastName"]}</td></tr>";
						}
						echo $table . "</table>";
						echo "<input type=\"hidden\" name=\"service_id\" value=\"{$service_id}\">";
						echo "<input type=\"hidden\" name=\"time\" value=\"{$_GET["time"]}\">";
					?>
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