<!DOCTYPE html>
<head>
    <meta charset="utf=8">
    <style>
		body {
			background-color: #e0f0ff;
		}
        #register_header, #register_form{
            max-width: fit-content;
            margin-inline: auto;
        }
    </style>
</head>
<body>
    <div id="register_header">
        <h2>Proszę się zarejestrować</h2>
    </div>
    <div id="register_form">
        <form action="register.php" method="POST">
            Email:<br>
            <input type="text" name="email" id="email"><br>
            Imię:<br>
            <input type="text" name="name" id="name"><br>
            Nazwisko:<br>
            <input type="text" name="last_name" id="last_name"><br>
            Data urodzenia:<br>
            <input type="date" name="date_of_birth" id="date_of_birth"><br>
            Pełny adres:<br>
            <input type="text" name="address" id="address"><br>
            Numer telefonu:<br>
            <input type="text" name="phone" id="phone"><br>
            Hasło:<br>
            <input type="password" name="password" id="password"><br>
            Powtórz hasło:<br>
            <input type="password" name="confirm_password" id="confirm_password"><br><br>
            <input type="submit" value="Zarejestruj"><br>
        </form>
        <?php

        require_once "config.php";

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            $email_pattern = "/^.+@.+\..{1,3}$/i";
            $phone_pattern = "/^(\+\d\d)?\d{9}$/";
            $phone_nr = str_replace(" ", "", $_POST["phone"]);

            $ret = false;

            if(!preg_match($email_pattern, trim($_POST["email"]))) {
                echo "Nieprawidłowy email<br>";
                $ret = true;
            }
            if(empty(trim($_POST["name"]))) {
                echo "Imię nie może być puste<br>";
                $ret = true;
            }
            if(empty(trim($_POST["last_name"]))) {
                echo "Nazwisko nie może być puste<br>";
                $ret = true;
            }
            if(empty(trim($_POST["date_of_birth"]))) {
                echo "Data urodzenia nie może być pusta<br>";
                $ret = true;
            }
            if(empty(trim($_POST["address"]))) {
                echo "Adres nie może być pusty<br>";
                $ret = true;
            }
            if(!preg_match($phone_pattern, trim($phone_nr))) {
                echo "Nieprawidłowy numer telefonu<br>";
                $ret = true;
            }
            if(empty(trim($_POST["password"]))) {
                echo "Hasło nie może być puste<br>";
                $ret = true;
            }
            if(trim($_POST["password"]) != trim($_POST["confirm_password"])) {
                echo "Hasła się nie zgadzają<br>";
                $ret = true;
            }
            if($ret) return;
        
            echo "Zarejestrowano!";
        }
        ?>
    </div>
    <br>
</body>