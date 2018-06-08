<?php # This script runs once a day.
include "include/connect.php";

# Set users offline that haven't been active for more then 25 mins

$sql = "SELECT last_active,id FROM `users` WHERE `online`='1'";
$result = mysqli_query($connection,$sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $d = date("Y-m-d H:i:s");
        $d1 = date_create($d);
        $d2 = date_create($row['last_active']);
        $diff = date_diff($d1,$d2);
        $time = ($diff->format('%H') * 60 * 60) + ($diff->format('%i') * 60) + $diff->format('%s'); 
        if ($time > 1500) {
            $id = $row['id'];
            $sql = "UPDATE `users` SET online='0' WHERE id=$id";
            mysqli_query($connection,$sql);
        }?>
        <p><?php if ($time > 1500) { 
            echo "true"; 
        } else { 
            echo "false"; 
        } ?></p>
    <?php } 
}

# Puzzle history on profile

$sql = "SELECT rating,id FROM `users` WHERE updated_history='1'";
$result = mysqli_query($connection,$sql);
if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $rating = $row['rating'];
        $user = $row['id'];
        $date = date("Y-m-d")." 00:00:00";
        $sql = "INSERT INTO `puzzles_history` (`rating`,`user`,`profile`,`date`) VALUES ('$rating','$user','1','$date')";
        //echo $sql;
        mysqli_query($connection,$sql);
        mysqli_query($connection,"UPDATE `users` SET updated_history='0'");
    }
}
