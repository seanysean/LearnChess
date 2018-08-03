<?php
session_start();
include "../include/connect.php";
$uID = $_SESSION['userid'];
$sql = "UPDATE `users` SET online='0' WHERE id='$uID'";
$result = mysqli_query($connection,$sql);
if ($result) {
    session_destroy();
    header('Location: /');
}
