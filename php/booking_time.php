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
				<?php
				/*
					+----------------------------------------+
					| LASCIATE OGNE SPERANZA, VOI CH'INTRATE |
					+----------------------------------------+
				*/

					require_once "config.php";

					if(!isset($_GET["service_id"])) {
						echo "<h2>Nie wybrano usługi! Proszę wrócić</h2>";
						return;
					}

					// get some useful shit
					$db->real_query("SELECT StaffID, FirstName, LastName FROM Staff");
					$rows = $db->use_result();
					$staff = array();
					while($row = $rows->fetch_assoc()) {
						array_push($staff,  $row);
					}

					$stmt = $db->prepare("SELECT DurationHalfHours FROM Services WHERE ServiceID = ?");
					$service_id = $_GET["service_id"];
					$stmt->bind_param("i", $service_id);
					$stmt->execute();
					$res = $stmt->get_result();
					$visit_duration = $res->fetch_assoc()["DurationHalfHours"];

					// we get all existing appointments after today to $res
					$date_now = date("Y-m-d H:i");
					$db->real_query("SELECT AppointmentDate, DurationHalfHours, StaffID FROM Appointments " .
									"JOIN Services USING (ServiceID) WHERE AppointmentDate > \"{$date_now}\"");
					$rows = $db->use_result();
					$res = array();
					while($row = $rows->fetch_assoc()) {
						array_push($res, $row);
					}

					// construct the availability table
					$availability = array();
					$date_i = date("Y-m-d", strtotime($date_now));
					for($i = 0; $i < 7; $i++) {
						// add one day
						$date_i = date("Y-m-d", strtotime($date_i . " +1 day"));
						$availability[$date_i] = [];
						
						$time_i = "08:00";
						// 16 half hour periods between 8 and 16
						for($j = 0; $j < 16; $j++) {
							$availability[$date_i][$time_i] = [];
							foreach($staff as $s) {
								// available, we'll change that later
								$availability[$date_i][$time_i][$s["StaffID"]] = true;
							}
							$time_i = date("H:i", strtotime($time_i . " +30 minutes"));
						}
					}

					// update availability table
					foreach($res as $row) {
						$date = date("Y-m-d", strtotime($row["AppointmentDate"]));
						$time = date("H:i", strtotime($row["AppointmentDate"]));
						$sid = $row["StaffID"];
						$dur = $row["DurationHalfHours"];

						for($i = 0; $i < $dur; $i++) {
							$availability[$date][$time][$sid] = false;
							// update time for visits longer than 30min
							$time = date("H:i", strtotime($time . " +30 minutes"));
						}
					}

					$table = "<table><tr>";
					foreach($availability as $d => $arr) {
						$table .= "<th>{$d}</th>";
					}
					$table .= "</tr>";

					$time = "08:00";
					// loop over hours (every row is an hour)
					// 17 - duration bc for example we dont show 15:30 for a 2h visit
					for($i = 0; $i < 17 - $visit_duration; $i++) {
						$table .= "<tr>";

						// loop over days (column is a day)
						foreach($availability as $d => $arr) {
							// check availability of each doctor
							// (yes, another loop)

							// This is a prototype okay? I want it to be done /quickly/
							// This is not a production ready code and it's not meant to be it.
							// In prod I'd separate the shit out of everything into its multiple functions and modules.
							// But I've already learnt from other subjects on uni that it's basically pointless.
							// Professors don't care about clean code.
							// They care about whether it works.
							// So be it.

							$avail_doc = array();

							foreach($staff as $s) {
								$time_tmp = $time;
								// we get availability of a doctor by looping over all the time widows the visit would take
								$avail = true;
								for($j = 0; $j < $visit_duration; $j++) {
									if(!$availability[$d][$time_tmp][$s["StaffID"]]) {
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
								$table .= "<td><input type=\"radio\" name=\"time\" value=\"{$d} {$time}\" disabled=\"true\"><a style=\"color: grey\">{$time}</a></td>";
							}
							else {
								$table .= "<td><input type=\"radio\" name=\"time\" value=\"{$d} {$time}\">{$time}</td>";
							}
						}
						$table .= "</tr>";

						$time = date("H:i", strtotime($time . " +30 minutes"));
					}

					echo $table . "</table><input type=\"hidden\" name=\"service_id\" value=\"{$service_id}\">";
				?>
			</div>
			<div id="submit_button" class="button">
				<input type="submit" value="Dalej">
			</div>
		</form>
	</div>
</body>
</html>