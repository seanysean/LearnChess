<?php
require "include/functions.php";
header('Content-Type','text/html');
if(isset($_GET['u'])) {
    $uID = $_GET['u'];
    $sql = "SELECT * FROM `users` WHERE id='$uID'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $r = $result->fetch_assoc();
        $closed = $r['active'] === '1' ? false : true;
        $online = $r['online'] === '1' ? 'online' : 'offline';
        $username = $r['username'];
        $user = strtolower($username);
        $link = $r['lichess'] ? "<a target=\"_blank\" href=\"https://lichess.org/@/".strtolower($r['lichess'])."\">Lichess <i class=\"fa fa-external-link\"></i></a>" : "<a href=\"/member/$user\">Profile</a>";
        if ($closed) {
            echo "<div class=\"ui-top\"><a class=\"uilink\" href=\"/member/$user\"><i class=\"fa fa-circle state $online\"></i> <span>$username</span></a></div><div class=\"padding no-account\">Account closed</div>";
        } else {
            echo "<div class=\"ui-top\"><a class=\"uilink\" href=\"/member/$user\"><i class=\"fa fa-circle state $online\"></i> <span>$username</span></a></div><div class=\"padding\">$link</div>";
        }
    } else {
        echo "<div class=\"padding no-account\">Account not found</p>";
    }
}
