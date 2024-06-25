<?php
session_start();
include 'settings.php';
//check users
$room = $_SESSION['room'];



while(True)
{
    $query = "SELECT * FROM players WHERE room='$room'";
    $run = mysqli_query($db, $query);
    if ($run) {
        $players = mysqli_fetch_array($run);
    }
    sleep(0.5);
}


//player join
if (isset($_POST['newplayer'])) {
    //check if room is active
    $name = htmlspecialchars($_POST['name']);
    $roomcode = htmlspecialchars($_POST['roomcode']);
    $query = "SELECT * FROM rooms WHERE roomcode='$roomcode' AND state='LIVE'";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_row($result) == 1) {
        //check for user conflict
        $query = "SELECT * FROM players WHERE name='$name'";
        $result = mysqli_query($db, $query);
        if (mysqli_fetch_row($result) > 0) {
            $log = "user exists already for this room";
        } else {
            $query = "INSERT INTO players (name,roomcode) VALUES('$name','$roomcode')";
            if (mysqli_query($db, $query)) {
                $_SESSION['player'] = $name;
                echo "player added";
            }
        }
    } else {
        $log = "room does not exist";
    }
    echo $log;
}

