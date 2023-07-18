<?php
    function textValidation($text) {
        if(empty($text)) {
            return "Enter value";
        }
        elseif(ctype_alpha(str_replace(" ", "", $text)) == false) {
            return "The field can only contains letters";
        }
        elseif(strlen($text) > 50) {
            return "Field must be less than 50 characters";
        }
        else {
            return false;
        }
    }

    function dateValidation($date) {
        $year = (int)substr($date, 0, 4);
        if ($year < 1900) {
            return "Year of birth can't be before 1900";
        } else {
            return false;
        }
    }

    function usernameValidation($username, $conn) {
        $q = "SELECT `username`
              FROM users
              WHERE `username` LIKE '$username'";

        $result = $conn->query($q);

        if(empty($username)) {
            return "Enter value";
        }
        elseif(preg_match("/\s/", $username)) {
            return "Username can't contains spaces";
        }
        elseif(strlen($username) < 5 || strlen($username) > 50) {   
            return "Username must be betweeen 5 and 50 characters";
        }
        elseif($result->num_rows) {
            return "Username already exists in database";
        } 
        else {
            return false;
        }
    }

    function passwordValidation($password) {    
        if(empty($password)) {
            return "Enter value";
        } elseif(preg_match("/\s/", $password)) {
            return "Password can't contains spaces";
        } elseif(strlen($password) < 5 || strlen($password) > 50) {   
            return "Password must be betweeen 5 and 50 characters";
        } else {
            return false;
        }
    }


    function retypePasswordValidation($password, $passCheck) {    
        if(empty($password)) {
            return "Enter value";
        } elseif(preg_match("/\s/", $password)) {
            return "Password can't contains spaces";
        } elseif(strlen($password) < 5 || strlen($password) > 50) {   
            return "Password must be betweeen 5 and 50 characters";
        } else {
            return false;
        }
    }