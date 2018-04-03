<?php
session_start();
require "include/functions.php";
if(!$l) {
    header('Location: /');
} else {
    if (isset($_GET['_'])) {
        $userID = $_SESSION['userid'];
        $sql = "SELECT * FROM `notifications` WHERE `to_id`='$userID' LIMIT 10";
        $result = mysqli_query($connection,$sql);
        $num = mysqli_num_rows($result);
        $return = '[';
        $c_row = 0;
        if ($num) {
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                $c_row++;
                $res = Array('icon' => $row['icon'], 'message' => $row['message'], 'unread' => $row['unread']);
                $return .= json_encode($res);
                if ($c_row < $num) {
                    $return .= ',';
                }
            }
            echo $return.']';
        } else {
            echo '[ {"message":"-"} ]';
        }
    }
}
