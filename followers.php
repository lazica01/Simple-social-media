<?php 
    require_once "header.php";
    require_once "connection.php";

    if(empty($_SESSION['id'])) {
        header("Location: login.php");
    }

    $id = $_SESSION['id']; // ID ulogovanog korisnika

    // Ako je nesto upisano u follow, ako postoji
    if(isset($_GET['follow'])) {
        $friendId = $conn->real_escape_string($_GET['follow']);
        // echo $friendId;

        // Ja nekoga treba da zapratim
        $q = "SELECT * FROM `followers`
              WHERE sender_id = $id AND recever_id = $friendId";
        $res = $conn->query($q);
        if($res->num_rows == 0) {
            $q = "INSERT INTO followers(sender_id, recever_id)
                  VALUES($id, $friendId)";
            $res = $conn->query($q);
        }
    }

    // Ako je nesto upisano u unfollow, ako postoji
    if(isset($_GET['unfollow'])) {
        $friendId = $conn->real_escape_string($_GET['unfollow']);

        $q = "DELETE FROM followers
              WHERE sender_id = $id AND recever_id = $friendId";
        $res = $conn->query($q);
    
    }

    $q = "SELECT u.id, CONCAT(p.name, ' ', p.surname) AS 'full_name', u.username
          FROM users AS u
          INNER JOIN profiles AS p 
            ON p.user_id = u.id 
          WHERE u.id != $id";

    $result = $conn->query($q);

    if($result->num_rows == 0) {
        echo "<p class='error'>No users in database</p>";
    } else {
        echo "<table id='followers'>";
        echo "<tr>
                <th>Full Name</th>
                <th>Username</th>
                <th>Action</th>
              </tr>";
        foreach($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['full_name'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";

            $friendId = $row['id'];

            $q1 = "SELECT *
                  FROM followers
                  WHERE sender_id = $id AND recever_id = $friendId";


            $res1 = $conn->query($q1);
            $f1 = $res1->num_rows; // 0 ili 1 je rezultat koji ce se upisati u f1


            $q2 = "SELECT *
                  FROM followers
                  WHERE sender_id = $friendId AND recever_id = $id";


            $res2 = $conn->query($q2);
            $f2 = $res2->num_rows; 
            if($f1 == 0) {
                if($f2 == 0) {
                    $status = "Follow";
                } else {
                    $status = "Follow back";
                }

                echo "<td><a href='followers.php?follow=$friendId'>" . $status . "</a></td>";
                
            } else {
                $status = "Unfollow";

                echo "<td><a href='followers.php?unfollow=$friendId'>" . $status . "</a></td>";
            }



            echo "</tr>";
        }

        echo "</table>";
    }


?>