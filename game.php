<?php
session_start();
include "./settings.php";

$roomcode = $_SESSION['room'];
//check the room states if it's active
$query = "SELECT * FROM rooms WHERE roomcode='$roomcode' AND state='ACTIVE'";
$result = mysqli_query($db, $query);
if (mysqli_num_rows($result) > 0) {
    //set player questions
    if (isset($_POST['set question'])) {
        $player = $_SESSION['player'];
        $truth = htmlspecialchars($_POST['truth']);
        $dare = htmlspecialchars($_POST['dare']);
        //set questions in db
        $query = "INSERT INTO players (roomcode,player,truth,dare) VALUES('$roomcode','$player','$truth','$dare')";
        if (mysqli_query($db, $query)) {
            echo "filled successfully";
        }
    }
    //start game
    if (isset($_GET['start'])) {
        $room = $_GET['start'];
        if (mysqli_query($db, "UPDATE rooms SET state='ACTIVE'  WHERE roomcode=$room")) {
            echo "game started";
        }
        $_SESSION['room'] = $room;
    }

    //collect the players data and mix
    function collect_mix($roomcode)
    {
        global $log, $db;
        // check questions
        $query = "SELECT * FROM questions WHERE roomcode ='$roomcode' and state='' ";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) > 0) {
            $questions = mysqli_fetch_array($results);
            $head = shuffle($questions);
            // check players
            $query = "SELECT * FROM players WHERE roomcode ='$roomcode' and played='' ";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) > 0) {
                $questions = mysqli_fetch_array($results);
                $tails = shuffle($questions);
                return [$head, $tails];
            }
        } else {

            $log = "No questions found";
        }
    }

    // send next users
    if ($_GET['next']) {
        $task = collect_mix($roomcode);
        $heads = $task[0][0]; //question array
        $tails = $task[1][0]; //player array
        $x = $heads[1];
        // update states to live
        if (mysqli_query($db, "UPDATE question SET state='LIVE'  WHERE player=$x")) {
            $x = $tails[1];
            if (mysqli_query($db, "UPDATE players SET played='LIVE'  WHERE name=$x")) {
            } else {
                $log = "issues paring";
            }
        }
    }

    while (True) {
        $query = "SELECT * FROM question WHERE state='LIVE'";
        $run = mysqli_query($db, $query);
        if ($run) {
            $live_question = mysqli_fetch_array($run);
            $query = "SELECT * FROM players WHERE played='LIVE'";
            $run = mysqli_query($db, $query);
            if ($run) {
                $live_head = mysqli_fetch_array($run);
            }
        }
        sleep(0.5);
    }




    //done with question
    if ($_GET['done']) {
        $x = $heads[1];
        if (mysqli_query($db, "UPDATE question SET state='USED'  WHERE player=$x")) {
            $x = $tails[1];
            if (mysqli_query($db, "UPDATE players SET played='PLAYED'  WHERE name=$x")) {
            } else {
                $log = "issues moving on";
            }
        }
    }

    // skipped user
    if ($_POST['skip']) {
        $player = $_POST['skip'];
        if (mysqli_query($db, "UPDATE players SET played='SKIPPED'  WHERE name=$x")) {
        }
    }


    //end game
    if (isset($_GET['code'])) {
        // clear data base of room and players
        $roomcode = $_GET['code'];
        $query = "DELETE FROM players WHERE roomcode = '$roomcode'";
        if (mysqli_query($db, $query)) {
            //clear room players
            $query = "DELETE FROM rooms WHERE roomcode = '$roomcode'";
            if (mysqli_query($db, $query)) {

                echo "done";
            }
        }
    }
} else {
    $log = "room does not exist";
}
echo $log;
