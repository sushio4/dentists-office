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
    <script src="/welcome.js"></script>
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
            <div id="table">
            </div>
            <br>
            <form action="past_visits.php" class="center" style="transform: scale(2); margin-bottom: 30px">
                <input type="submit" value="Poprzednie wizyty">
            </form>
        </div>
    </div>
</body>