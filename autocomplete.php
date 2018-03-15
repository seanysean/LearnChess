<?php
header('Content-Type', 'application/json');
require "include/connect.php";
if(isset($_GET['exists']) && $_GET['exists'] === "1" && isset($_GET['username'])) {
    $input = $_GET['username'];
    $sql = "SELECT username FROM `users` WHERE username='$input' LIMIT 1";
    $result = mysqli_query($connection,$sql);
    $count = mysqli_num_rows($result);
    if ($count === 1) {
        $return = Array('exists' => true);
    } else {
        $return = Array('exists' => false);
    }
    echo json_encode($return);
}
