<!DOCTYPE html>
<html>
<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: welcome.php");
        exit;
    }
?>
<head>
    <meta charset="utf=8">
    <style>
		body {
			background-color: #e0f0ff;
		}
        #login_header, #login_form, #register, #feedback {
            max-width: fit-content;
            margin-inline: auto;
        }
    </style>
    <title>Logowanie</title>
</head>
<body>
    <div id="login_header">
        <h2>Proszę się zalogować</h2>
    </div>
    <div id="login_form">
        <form action="index.php" method="POST">
            Email:<br>
            <input type="text" name="email" id="email"><br>
            Hasło:<br>
            <input type="password" name="password" id="password"><br><br>
            <input type="submit" value="Zaloguj"><br>
        </form>
        <h3>Nie pamiętasz hasła?</h3>
        <form action="forgor.php">
            <input type="submit" value="Nie pamiętam hasła">
        </form>
    </div>
    <br>
    <div id="register">
        <h3>Nie masz konta?</h3>
        <form action="/register.php">
            <input type="submit" value="Zarejestruj się">
        </form>
        <br>
    </div>
    <div id="feedback">
        <?php

        require_once "config.php";

        // check if user completed the form
        if($_SERVER["REQUEST_METHOD"] == "POST") {

            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);

            if(empty($email)) {
                echo "Email nie może być pusty<br>";
                return;
            }
            if(empty($password)) {
                echo "Hasło nie może być puste<br>";
                return;
            }

            $sql = "SELECT PatientID, FirstName, Pass FROM Patients WHERE Email = ?";
            $stmt = $db->prepare($sql);

            if(!$stmt) {
                echo "Please contact tech support, error code: ID10-T";
                return;
            }
            // maybe not the best syntax but it's manageable
            //
            // "s" is for string param, "i" is "int", "d" is double, "b" is for blob (whatever the fuck that is...)
            //
            // you can put multiple params like so:
            //      ... bind_param("iss", $id, $email, $other_text);
            //
            // Oh and they must be variables bc it passes by reference
            $stmt->bind_param("s", $email);

            if($stmt->execute()) {
                $stmt->store_result();

                $user_id = 0;
                $hashed_pass = "";
                $name = "";

                if($stmt->num_rows != 1) {
                    echo "Użytkownik nie istnieje. Proszę się zarejestrować";
                    $stmt->close();
                    return;
                }
                
                //yup, that's how we get the results
                $stmt->bind_result($user_id, $name, $hashed_pass);

                if(!$stmt->fetch()) {
                    echo "Please contact tech support, error code: ID10-T";
                    $stmt->close();
                    return;
                }
                
                // remember to close the statement!
                $stmt->close();

                if(password_verify($password, $hashed_pass)) {
                    echo "Zalogowano";
                    $_SESSION["loggedin"] = true;
                    $_SESSION["name"] = $name;
                    $_SESSION["id"] = $user_id;

                    header("location: welcome.php");
                    return;
                }
                else {
                    echo "Niepoprawne hasło";
                    return;
                }
            }
        }
        ?>
    </div>
</body>
</html>