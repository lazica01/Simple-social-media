<?php
    require_once "connection.php";
    require_once "validation.php";

    $nameErr = $surnameErr = $dobErr = $usernameErr = $passwordErr = $retypePasswordErr = "";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $gender = $_POST["gender"];
        $dob = $_POST["dob"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $retypePassword = $_POST["retypePassword"];

        $validated = true;

        if(textValidation($name)) {
            $nameErr = textValidation($name);
            $validated = false;
        } else {
            $nameErr = "";
        }

        if(textValidation($name)) {
            $surnameErr = textValidation($name);
            $validated = false;
        } else {
            $surnameErr = "";
        }

        if(dateValidation($dob)) {
            $dobErr = dateValidation($dob);
            $validated = false;
        } else {
            $dobErr = "";
        }

        if(usernameValidation($username, $conn)) {
            $usernameErr = usernameValidation($username, $conn);
            $validated = false;
        } else {
            $usernameErr = "";
        }

        if(passwordValidation($password)) {
            $passwordErr = passwordValidation($password);
            $validated = false;
        } else {
            $passwordErr = "";
        }

        if(passwordValidation($retypePassword)) {
            $retypePasswordErr = passwordValidation($retypePassword);
            $validated = false;
        } elseif($password != $retypePassword) {
            $retypePasswordErr = "Password and retype password must match";
            $validated = false;
        } else {
            $oldPassword = $password;

            $password = md5($password);
        }

        if($validated) {

            $q = "INSERT INTO `users`(`username`, `pass`) VALUES ('$username', '$password');";
            
            if($conn->query($q)) {
                echo "<p>Successfully added user into table users</p>";

                $q = "SELECT `id`
                      FROM `users`
                      WHERE `username` LIKE '$username';";

                $result = $conn->query($q);
                $row = $result->fetch_assoc();
                $id = $row['id'];

                $q = "INSERT INTO `profiles`(`name`, `surname`, `gender`, `dob`, `user_id`) 
                      VALUES ('$name', '$surname', '$gender', '$dob', '$id');";

                if($conn->query($q)) {
                    echo "<p>Successfully added profile into table profiles</p>";
                } else {
                    echo "<p>Error in table profiles $conn->error</p>";
                }

            }

        }


    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
<form action="#" method="post">
        <p>
            Name:
            <input type="text" name="name" value="<?php if($name != undefined) {echo $name;} ?>"><span class="err"> * <?php echo $nameErr; ?></span>
        </p>
        <p>
            Surname:
            <input type="text" name="surname" value="<?php if($surname) echo $surname; ?>"><span class="err"> * <?php echo $surnameErr; ?></span>
        </p>
        <p>
            Gender:
            <input type="radio" name="gender" <?php if(isset($gender) && $gender == "m") echo "checked"; ?> value="m">Male
            <input type="radio" name="gender" <?php if(isset($gender) && $gender == "f") echo "checked"; ?> value="f">Female
            <input type="radio" name="gender" <?php if(isset($gender) && $gender == "o") echo "checked"; ?> value="o" checked>Other
        </p>
        <p>
            Data of birth:
            <input type="date" name="dob" value="<?php echo $dob; ?>"><span class="err"> * <?php echo $dobErr; ?></span>
        </p>
        <p>
            Username:
            <input type="text" name="username" value="<?php echo $username; ?>"><span class="err"> * <?php echo $usernameErr; ?></span>
        </p>
        <p>
            Password:
            <input type="password" name="password"><span class="err"> * <?php echo $passwordErr; ?></span>
        </p>
        <p>
            Retype password:
            <input type="password" name="retypePassword"><span class="err"> * <?php echo $retypePasswordErr; ?></span>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
</body>
</html>