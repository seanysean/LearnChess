<?php
session_start();
header('Content-Type', 'application/json');
include "../include/functions.php";
if (isset($_POST['trophy']) and isset($_POST['puzzle']) and $l) {
    $pID = secure($_POST['puzzle']);
    $sql = "SELECT `trophies`,`author_id` FROM `puzzles_approved` WHERE id='$pID'";
    $result = mysqli_query($connection,$sql);
    $res = $result->fetch_assoc();
    if ($res['author_id'] <> $_SESSION['userid']) {
        $t_countr = $res['trophies'];
        $t_count = $t_countr + 1;
        $sql2 = "UPDATE `puzzles_approved` SET trophies='$t_count' WHERE id='$pID'";
        $result2 = mysqli_query($connection,$sql2);
        if ($result2) {
            $return = Array('success' => true, 'count' => $t_count);
        } else {
            $return = Array('success' => false);
        }
    } else {
        $return = Array('error' => true, 'reason' => 'Trophy given by author');
    }
} else {
    $return = Array('error' => true);
    if (!$l) {
        $return['reason'] = 'Not logged in';
    }
}
echo json_encode($return);
