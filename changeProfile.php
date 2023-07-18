<?php
require_once "validation.php";
require_once "connection.php";
require_once "header.php";

if(empty($_SESSION['id'])) {
    header('Location: login.php');
}

$id = $_SESSION['id'];

$validated = true;
$name = $surname = $gender = $dob = "";
$nameErr = $surnameErr = $dobErr = "";

 if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    // Name validation
    if(textValidation($name)){
        $validated = false;
        $nameErr = textValidation($name);
    }
    else {
        $name = trim($name); 
        $name = preg_replace('/\s\s+/', ' ', $name); 
    }

    // Surname validation
    if(textValidation($surname)){
        $validated = false;
        $surnameErr = textValidation($surname);
    }
    else {
        $surname = trim($surname);
        $surname = preg_replace('/\s\s+/', ' ', $surname); 
    }

    // Date of birth validation
    if(dateValidation($dob)){
        $validated = false;
        $dobErr = dateValidation($dob);
    }

    
    if($validated){
        // Ako je validacija prošla, onda uradi update profila
        $q = "UPDATE `profiles`
              SET `name` = '$name', `surname` = '$surname', `gender` = '$gender', `dob` = '$dob'
              WHERE `user_id` = '$id'; ";
        $res = $conn->query($q);
    }
    
 }
 
 $q = "SELECT * FROM `profiles` WHERE `user_id` = '$id'";
 $res = $conn->query($q);
 $row = $res->fetch_assoc();

 $name = $row['name'];
 $surname = $row['surname'];
 $gender = $row['gender'];
 $dob = $row['dob'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="#" method="post">
        <p>
            Name:
            <input type="text" placeholder="Name" name="name" value="<?php if(isset($name)) echo $name; ?>">
            <span class="err">* <?php echo $nameErr; ?></span>
        </p>
        <p>
            Surname:
            <input type="text" placeholder="Surname" name="surname" value="<?php if(isset($surname)) echo $surname; ?>">
            <span class="err">* <?php echo $surnameErr; ?></span>
        </p>
        <p>
            Gender:
            <input type="radio" name="gender" value="m" <?php if(isset($gender) &&  $gender=="m"){echo 'checked';} ?>>Male
            <input type="radio" name="gender" value="f" <?php if(isset($gender) &&  $gender=="f"){echo 'checked';} ?>>Female
            <input type="radio" name="gender" value="o" <?php if(isset($gender) &&  $gender!="m" && $gender!="f"){echo 'checked';} ?>>Other
        </p>
        <p>
            Data of birth:
            <input type="date" placeholder="Date of birth" name="dob" value="<?php if(isset($dob)) echo $dob; ?>">
            <span class="err"><?php echo $dobErr; ?></span>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
</form>
</body>
</html>