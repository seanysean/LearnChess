<?php
session_start();
header('Content-Type', 'application/json');
include "../include/functions.php";
if (isset($_POST['trophy']) and isset($_POST['puzzle']) and $l) {
    $pID = $_POST['puzzle'];
    $sql = "SELECT trophies FROM `puzzles_approved` WHERE id='$pID'";
    $result = mysqli_query($connection,$sql);
    $t_countr = $result->fetch_assoc()['trophies'];
    $t_count = $t_countr + 1;
    $sql2 = "UPDATE `puzzles_approved` SET trophies='$t_count' WHERE id='$pID'";
    $result2 = mysqli_query($connection,$sql2);
    if ($result2) {
        $return = Array('success' => true, 'count' => $t_count);
    } else {
        $return = Array('success' => false);
    }
} else {
    $return = Array('error' => true);
    if (!$l) {
        $return['reason'] = 'Not logged in';
    } else if (!isset($_POST['trophy']) and !isset($_POST['puzzle'])) {
        $return['reason'] = 'You don\'t understand $_POST';
    }
}
echo json_encode($return);
