<?php
session_start();
include 'settings.php';

//include("db.php");

$log="";

//register user 
if (isset($_POST['register'])) {
    //convert pass to md5
    $username = htmlspecialchars($_POST['username']);
    $username = strtolower($username);
    $password = htmlspecialchars($_POST['pass1']);
    $password = md5($password);
    //query existing user
    if ($_POST['pass1'] == $_POST['pass2']) {

        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($db, $query);
        if (mysqli_fetch_row($result) > 1) {
            $log = "user already exists";
        } else {
            //register new user
            $query = "INSERT INTO users (username,password) VALUES('$username','$password')";
            if (mysqli_query($db, $query)) {
                $log = "user registered";
                $_SESSION['user'] = $username;
                header("location: ./view.php");
                echo "user created";
            }
        }
    } else {
        $log = "pass not matching";
    }
    echo $log;
}
//login user
if (isset($_POST['login'])) {
    //convert pass to md5
    $username = htmlspecialchars($_POST['username']);
    $username = strtolower($username);
    $password = htmlspecialchars($_POST['password']);
    $password = md5($password);
    $query = "SELECT * from users WHERE username='$username' and password ='$password'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        //create new suer session and redirect to main page
        session_regenerate_id();
        $_SESSION['user'] = $username;
        echo"logged in";
        //header("location: home.php?username=949959595");
    } else {
        $log = "incorrect information try again";
    }
    echo $log;
}

//host game
if (isset($_POST['host'])) {
    $name = $_POST['roomname'];
    $roomcode = rand(000000, 999999);
    //prevent room conflict
    $query = "SELECT * from rooms WHERE roomcode='$roomcode'";
    $result = mysqli_query($db, $query);

    if (mysqli_fetch_row($result) > 1) {
        $log = "room exists generate new";
        //create new suer session and redirect to main page
    } else {
        $query = "INSERT INTO rooms (roomname,roomcode,state) VALUES ('$name','$roomcode','LIVE')";
        if (mysqli_query($db, $query)) {
            $log = "room started";
            //set room session 
            $_SESSION['room'] = $roomcode;
           echo "room hosted";
        } else {
            $log = "could not create room";
        }
    }
}
