<?php
session_start();
require "../../include/functions.php";
header('Content-Type','application/json');
if(isset($_POST['score']) && $l) {
    $score = secure($_POST['score']) + 0;
    $u = $_SESSION['userid'];
    $sql = "UPDATE `users` SET coordinates='$score' WHERE id='$u'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        echo '{"success":true}';
    } else {
        echo '{"success":false}';
    }
}
if(isset($_GET['old']) && $l) {
    $u = $_SESSION['userid'];
    $sql = "SELECT coordinates FROM `users` WHERE id='$u'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $score = $result->fetch_assoc()['coordinates'];
        echo "{\"old\":$score}";
    }
}
