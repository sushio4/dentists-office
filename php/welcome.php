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
	<title>Ząbex - klinika dentystyczna</title>
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
		table {
			border-collapse: collapse
		}
		table, th, td {
			border: 2px ridge black;
			background-color: #d0f0ff;
		}
        .center {
            max-width: fit-content;
            margin-inline: auto;
        }
        #main_div {
            margin-top: 10px;
            display: flex;
            gap: 10px;
            align-items: stretch;
        }
        #left_panel, #right_panel {
            flex-grow: 1;
            background-color: #e0f0ff;
        }
	</style>
	<meta charset="utf-8">
</head>
<body>
	<header>
		<div id="profile">
			<form action="profile.php">
				<input type="submit" value="Mój profil" class="button">
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
        <div id="left_panel">
            <h1 class="center">Witaj ponownie, <?php
                //fajne rzeczy można tym pehapem robić 
                echo $_SESSION["name"]
            ?>!</h1>
            <br>
            <form action="/booking_visit.php" class="center">
                <input type="submit" value="Zapisz się na wizytę" class="button" style="margin-bottom: 40px">
            </form>
        </div>
        <div id="right_panel">
            <h1 class="center">Moje nadchodzące wizyty</h1>
            <br>
            <?php
                require_once "config.php";

                $sql = "SELECT ServiceName, DurationHalfHours, AppointmentDate, Staff.FirstName AS FirstName, Staff.LastName AS LastName, Price " .
                        "FROM Appointments " .
                        "JOIN Services USING (ServiceID) " . 
                        "JOIN Staff USING (StaffID) " .
                        "WHERE PatientId = ? AND AppointmentDate >= CURDATE() " .
                        "ORDER BY AppointmentDate ASC LIMIT 12";
                
                $stmt = $db->prepare($sql);
                if(!$stmt) {
                    echo "Contact tech support, error code: ID10-T";
                    return;
                }

                $user_id = $_SESSION["id"];
                $stmt->bind_param("i", $user_id);
                if($stmt->execute()) {
                    $res = $stmt->get_result();

                    if($res->num_rows == 0) {
                        echo "<h2 class=\"center\">Pusto!</h2>";
                        $stmt->close();
                        return;
                    }
                    
                    $table = "<table class=\"center\" style=\"margin-bottom: 30px; transform: scale(1.3);\"><tr><th>Usługa</th><th>Data</th><th>Czas</th><th>Lekarz</th><th>Cena</th></tr>\n";
                    while($row = $res->fetch_assoc()) {
                        $date = substr($row["AppointmentDate"], 0, 10);

                        // God forgive me for what I've done
                        // In my defense, dates and times are a bitch
                        $time_begin = date("H:i", strtotime(substr($row["AppointmentDate"], 11)));

                        $duration_mins = $row["DurationHalfHours"] * 30;
                        $time_end = date("H:i", strtotime($time_begin . "+{$duration_mins} minutes"));
                        $time = "{$time_begin} - {$time_end}";

                        // Oh and . concatenates strings so .= appends to a string
                        $table .= "<tr><td>{$row["ServiceName"]}</td><td>{$date}</td><td>{$time}</td>" . 
                                    "<td>{$row["FirstName"]} {$row["LastName"]}</td><td>{$row["Price"]}</td></tr>";
                    }
                    $table .= "</table>";
                    echo $table;
                }
                else {
                    echo "Contact tech support, error code: ID10-T";
                    $stmt->close();
                    return;
                }

            ?>
            <br>
            <form action="past_visits.php" class="center" style="transform: scale(2); margin-bottom: 30px">
                <input type="submit" value="Poprzednie wizyty">
            </form>
        </div>
    </div>
</body>