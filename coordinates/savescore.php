<?php
session_start();
require "../include/functions.php";
header('Content-Type','application/json');
if(isset($_POST['score']) && $l) {
    $score = secure($_POST['score']) + 0;
    $u = $_SESSION['username'];
    $sql = "UPDATE `users` SET coordinates='$score' WHERE username='$u'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        echo '{"success":true}';
    } else {
        echo '{"success":false}';
    }
}
