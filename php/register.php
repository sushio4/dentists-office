<!DOCTYPE html>
<head>
    <meta charset="utf=8">
    <style>
		body {
			background-color: #e0f0ff;
		}
        #register_header, #register_form, #login, #feedback{
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
    </div>
    <div id="login">
        <h3>Masz już konto?</h3>
        <form action="/index.php">
            <input type="submit" value="Zaloguj się">
        </form>
    </div>
    <br>
    <div id="feedback">
        <?php

        require_once "config.php";

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            // yup, this is regex. fuck me
            $email_pattern = "/^.+@.+\..{1,3}$/i";
            $phone_pattern = "/^(\+\d\d)?\d{9}$/";

            // just an indicator whether something went wrong
            // it's there so you can print multiple error messages before returning
            $ret = false;

            $email = trim($_POST["email"]);
            $name = trim($_POST["name"]);
            $last_name = trim($_POST["last_name"]);
            $date_of_birth = trim($_POST["date_of_birth"]);
            $address = trim($_POST["address"]);
            $phone_nr = str_replace(" ", "", $_POST["phone"]);
            $password = trim($_POST["password"]);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if(!preg_match($email_pattern, $email)) {
                echo "Nieprawidłowy email<br>";
                $ret = true;
            }
            if(empty($name)) {
                echo "Imię nie może być puste<br>";
                $ret = true;
            }
            if(empty($last_name)) {
                echo "Nazwisko nie może być puste<br>";
                $ret = true;
            }
            if(empty($date_of_birth)) {
                echo "Data urodzenia nie może być pusta<br>";
                $ret = true;
            }
            if(empty($address)) {
                echo "Adres nie może być pusty<br>";
                $ret = true;
            }
            if(!preg_match($phone_pattern, $phone_nr)) {
                echo "Nieprawidłowy numer telefonu<br>";
                $ret = true;
            }
            if(empty($password)) {
                echo "Hasło nie może być puste<br>";
                $ret = true;
            }
            if($password != trim($_POST["confirm_password"])) {
                echo "Hasła się nie zgadzają<br>";
                $ret = true;
            }
            if($ret) return;

            // check if user exists
            $sql = "SELECT * FROM Patients WHERE Email = ?";
            $stmt = $db->prepare($sql);

            if(!$stmt) {
                echo "Please contact tech support, error code: ID10-T";
                return;
            }

            $stmt->bind_param("s", $email);

            if($stmt->execute()) {
                $stmt->store_result();
                
                if($stmt->num_rows > 0) {
                    echo "Użytkownik o takim adresie email już istnieje. Proszę się zalogować"; 
                    return;
                }
            }
            else {
                echo "Please contact tech support, error code: ID10-T";
                $stmt->close();
                return;
            }

            // insert data to db
            $sql = "INSERT INTO Patients (FirstName, LastName, DateOfBirth, Phone, Email, Address, Pass) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt->prepare($sql);

            if(!$stmt) {
                echo "Please contact tech support, error code: ID10-T";
                return;
            }

            $stmt->bind_param("sssssss", $name, $last_name, $date_of_birth, $phone_nr, $email, $address, $hashed_password);

            if($stmt->execute()) {
                echo "Pomyślnie zarejestrowano!";
                $user_id = $db->insert_id;

                session_start();

                $_SESSION["name"] = $name;
                $_SESSION["id"] = $user_id;
                $_SESSION["loggedin"] = true;

                header("location: welcome.php");
                exit;
            }
            else {
                echo "Coś poszło nie tak :c";
            }
            $stmt->close();
        }
        ?>
    </div>
    <br>
</body>