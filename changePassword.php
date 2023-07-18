<?php
require_once "connection.php";
require_once "header.php";
require_once "validation.php";

if(empty($_SESSION['id'])) {
    header('Location: login.php');
}

$id = $_SESSION['id'];

$successfulMsg = $newErr = $oldErr = $retypeErr = "";
$successful = true;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old = $_POST['oldPassword'];
    $new = $_POST['newPassword'];
    $retype = $_POST['retypePassword'];

    $old = md5($old);
    $q = "SELECT pass from users where id=$id";
    $res = $conn->query($q);
    $row = $res->fetch_assoc();
    if ($row['pass'] != $old) {
        $oldErr = "Old password does not match";
        $successful = false;
    }
    if (passwordValidation($new)) {
        $newErr = passwordValidation($new);
        $successful = false;
    }
    if ($new != $retype) {
        $retypeErr = "Passwords doesen't match";
        $successful = false;
    } else {
        $new = md5($new);
    }

    if ($successful) {
        $q = "UPDATE users
              SET pass='$new'
              WHERE id=$id";
        $conn->query($q);
        $successfulMsg = "<p class='err'>U successfully changed your password</p>";
    }
}

?>


<form action="#" method='post'>
    <p>Old password
        <input type="password" name="oldPassword" id="">
        <span class="err">* <?php echo $oldErr; ?></span>
    </p>
    <p>Enter new password
        <input type="password" name="newPassword" id="">
        <span class="err">* <?php echo $newErr; ?></span>
    </p>
    <p>Retype password
        <input type="password" name="retypePassword" id="">
        <span class="err">* <?php echo $retypeErr; ?></span>
    </p>
    <br>
    <input type="submit" value="Change">
    <p class="successful"><?php echo $successfulMsg; ?></p>
</form>

</body>

</html>