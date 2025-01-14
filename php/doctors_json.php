<?php
    require_once "config.php";

    $resp = array();
    $resp["success"] = true;

    if(!isset($_GET["service_id"])) {
        $resp["content"] = "Nie wybrano usługi! Proszę wrócić";
        $resp["success"] = false;
        goto L_end;
    }
    if(!isset($_GET["time"])) {
        $resp["content"] = "Nie wybrano terminu! Proszę wrócić";
        $resp["success"] = false;
        goto L_end;
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
        $resp["content"] = "Invalid GET arguments you dirty hacker!";
        $resp["success"] = false;
        goto L_end;
    }

    $cont = array();

    foreach($avail_doc as $doc) {
        // $table .= "<tr><td><input type=\"radio\" name=\"doctor\" value={$doc["StaffID"]}>{$doc["FirstName"]} {$doc["LastName"]}</td></tr>";
        array_push($cont, ["staff_id" => $doc["StaffID"], "name" => "{$doc["FirstName"]} {$doc["LastName"]}"]);
    }

    $resp["content"] = $cont;

    L_end:
    echo json_encode($resp);
?>