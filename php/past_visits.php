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
    <script src="/past_visits.js"></script>
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
        <div id="table">
        </div>
        <br>
        <div class="center">
            <form action="main_page.php">
                <input type="submit" value="Powrót" class="button">
            </form>
        </div>
    </div>
</body>
</html>
