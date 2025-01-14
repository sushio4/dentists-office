<?php
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: index.php");
        exit;
    }

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
            $tab = array();

            while ($row = $res->fetch_assoc()) {
                $date = substr($row["AppointmentDate"], 0, 10);
                $time_begin = date("H:i", strtotime(substr($row["AppointmentDate"], 11)));
                $duration_mins = $row["DurationHalfHours"] * 30;
                $time_end = date("H:i", strtotime($time_begin . "+{$duration_mins} minutes"));
                $time = "{$time_begin} - {$time_end}";

                array_push($tab, [
                    "service" => $row["ServiceName"],
                    "date" => $date,
                    "time" => $time,
                    "doctor" => "{$row["FirstName"]} {$row["LastName"]}",
                    "price" => $row["Price"]
                ]);
            }
            
            echo json_encode($tab);
        }
    } else {
        echo "<h3 class=\"center\">Wystąpił problem. Skontaktuj się z obsługą techniczną.</h3>";
    }
?>