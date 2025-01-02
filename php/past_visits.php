<!DOCTYPE html>
<html>
<?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: index.php");
        exit;
    }
?>
<head>
    <title>Poprzednie wizyty - Ząbex</title>
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
            border-collapse: collapse;
        }
        table, th, td {
            border: 2px ridge black;
            background-color: #d0f0ff;
        }
        .center {
            max-width: fit-content;
            margin-inline: auto;
        }
        #content {
            margin-top: 20px;
            padding: 20px;
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
        
        <h1>Ząbex - Poprzednie wizyty</h1>

        <div id="logout">
            <form action="logout.php">
                <input type="submit" value="Wyloguj" class="button">
            </form>
        </div>
    </header>
    <div id="content">
        <h2 class="center">Twoje poprzednie wizyty</h2>
        <br>
        <?php
            require_once "config.php";

            $sql = "SELECT ServiceName, DurationHalfHours, AppointmentDate, Staff.FirstName AS FirstName, 
                           Staff.LastName AS LastName, Price 
                    FROM Appointments 
                    JOIN Services USING (ServiceID) 
                    JOIN Staff USING (StaffID) 
                    WHERE PatientId = ? AND AppointmentDate < CURDATE() 
                    ORDER BY AppointmentDate DESC";

            $stmt = $db->prepare($sql);
            if (!$stmt) {
                echo "<h3 class=\"center\">Wystąpił problem. Skontaktuj się z obsługą techniczną.</h3>";
                return;
            }

            $user_id = $_SESSION["id"];
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                $res = $stmt->get_result();

                if ($res->num_rows == 0) {
                    echo "<h3 class=\"center\">Brak poprzednich wizyt do wyświetlenia.</h3>";
                } else {
                    $table = "<table class=\"center\"><tr><th>Usługa</th><th>Data</th><th>Czas</th>
                              <th>Lekarz</th><th>Cena</th></tr>";
                    while ($row = $res->fetch_assoc()) {
                        $date = substr($row["AppointmentDate"], 0, 10);
                        $time_begin = date("H:i", strtotime(substr($row["AppointmentDate"], 11)));
                        $duration_mins = $row["DurationHalfHours"] * 30;
                        $time_end = date("H:i", strtotime($time_begin . "+{$duration_mins} minutes"));
                        $time = "{$time_begin} - {$time_end}";

                        $table .= "<tr><td>{$row["ServiceName"]}</td><td>{$date}</td><td>{$time}</td>
                                   <td>{$row["FirstName"]} {$row["LastName"]}</td><td>{$row["Price"]}</td></tr>";
                    }
                    $table .= "</table>";
                    echo $table;
                }
            } else {
                echo "<h3 class=\"center\">Wystąpił problem. Skontaktuj się z obsługą techniczną.</h3>";
            }
        ?>
        <br>
        <div class="center">
            <form action="main_page.php">
                <input type="submit" value="Powrót" class="button">
            </form>
        </div>
    </div>
</body>
</html>
