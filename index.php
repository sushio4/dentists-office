<!DOCTYPE html>
<head>
    <meta charset="utf=8">
    <style>
		body {
			background-color: #e0f0ff;
		}
        #login_header, #login_form {
            max-width: fit-content;
            margin-inline: auto;
        }     
    </style>
</head>
<body>
    <div id="login_header">
        <h2>Proszę się zalogować</h2>
    </div>
    <div id="login_form">
        <form action="index.php" method="POST">
            Nazwa użytkownika:<br>
            <input type="text" name="username" id="username"><br>
            Hasło:<br>
            <input type="password" name="password" id="password"><br><br>
            <input type="submit" value="Zaloguj"><br>
        </form>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "Logged in as: {$_POST["username"]}";
        }
        ?>
    </div>
</body>