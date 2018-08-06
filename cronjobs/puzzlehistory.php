<?php # This script runs once a day.
include "../include/connect.php";

# Puzzle history on profile

$sql = "SELECT rating,id FROM `users` WHERE updated_history='1'";
$result = mysqli_query($connection,$sql);
if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $rating = $row['rating'];
        $user = $row['id'];
        $date = date("Y-m-d")." 00:00:00";
        $sql = "INSERT INTO `puzzles_history` (`rating`,`user`,`profile`,`date`) VALUES ('$rating','$user','1','$date')";
        mysqli_query($connection,$sql);
        mysqli_query($connection,"UPDATE `users` SET updated_history='0'");
    }
}
