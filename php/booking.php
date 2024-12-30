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
			background-color: #e0f0ff;
		}
		header {
			background-color: #b0d0ff;
			display: flex;
			justify-content: space-around;
			align-items: center;
			height: 10vh;
		}
		#logout_button {

		}
		table {
			border-collapse: collapse
		}
		table, th, td {
			border: 2px ridge black;
			background-color: #d0f0ff;
		}
		#timetable {
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
			<form action="profile.php">
				<input type="submit" value="Mój profil" id="profile_button">
			</form>
		</div>
		
		<h2>Witaj ponownie, <?php
			//fajne rzeczy można tym pehapem robić 
			echo $_SESSION["name"]
		?>!</h2>

		<div id=logout>
			<form action="logout.php">
				<input type="submit" value="Wyloguj" id="logout_button">
			</form>
		</div>
	</header>
	<div id="content">
		<div id="table_header">
		<h2>Dostępne godziny</h2>
		</div>
	    <form>
		<div id="timetable">
		<table>
	    <tr>
			<th>1.01</th>
			<th>2.01</th>
			<th>3.01</th>
		</tr>
		<tr>
        <td>
		<input type="radio" id="h8" name="time" value="8" disabled="true">
			<label for="h8">8:00</label>
        </td>
        <td>
		<input type="radio" id="h8" name="time" value="8">
			<label for="h8">8:00</label>
        </td>
        <td>
		<input type="radio" id="h8" name="time" value="8">
			<label for="h8">8:00</label>
        </td>
        </tr>
        <tr>
        <td>
		<input type="radio" id="h9" name="time" value="9" disabled="true">
			<label for="h9">9:00</label>
		</td>
		<td>
		<input type="radio" id="h9" name="time" value="9">
			<label for="h9">9:00</label>
		</td>
		<td>
		<input type="radio" id="h9" name="time" value="9" disabled="true">
			<label for="h9">9:00</label>
		</td>
        </tr>
		<tr>
        <td>
        <input type="radio" id="h10" name="time" value="10">
			<label for="h10">10:00</label>
		</td>
		<td>
        <input type="radio" id="h10" name="time" value="10">
			<label for="h10">10:00</label>
		</td>
		<td>
        <input type="radio" id="h10" name="time" value="10" disabled="true">
			<label for="h10">10:00</label>
		</td>
        </tr>
	    </table>
	    </div>
	    <br>
	    <div id="submit_button">
		<input type="submit" value="Potwierdź rezerwację">
		</div>
	</div>
</body>
</html>