<?php
	session_start();

	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
		header("location: index.php");
		exit;
	}

    require_once "config.php";

    // we don't need prepared statements because there is no risk of sql injection here
    $db->real_query("SELECT * FROM Services");
    $result = $db->use_result();

    $tab = array();

    foreach($result as $row) {
        $dur_mins = 30 * $row["DurationHalfHours"];
        $duration = date("H:i", strtotime("00:00 +{$dur_mins} minutes"));

        array_push($tab, [
            "service_id" => $row["ServiceID"],
            "name" => $row["ServiceName"],
            "duration" => $duration,
            "description" => $row["Description"],
            "price" => $row["Price"]
        ]);
    }
    
    echo json_encode($tab);
?>