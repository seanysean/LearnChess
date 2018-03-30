<?php
include "connect.php";
#l is true if user is logged in.
$l = isset($_SESSION['username']);

if ($l) {
    $currentSessionID = $_SESSION['userid'];
    $updateActivitySQL = "UPDATE `users` SET last_active=CURRENT_TIMESTAMP WHERE id='$currentSessionID'";
    mysqli_query($connection,$updateActivitySQL);
}

function secure($input,$nlines=false) {
    #nlines is for <textarea>s.
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = str_replace("'",'&#39;',$input);
    $input = str_replace('"','&quote;',$input);
    if ($nlines) {
        $input = preg_replace('/\n/','<br />',$input);
    }
    $input = trim($input);
    return $input;
}
function isAllowed($type) {
    # Valid $types: 'admin','puzzle'.
    global $l;
    if (!$l) {
        return false;
    }
    $pEdited = str_split($_SESSION['permissions']);
    if ($type === 'admin') {
        return ($pEdited[0] === '1') ? true : false;
    } else if ($type === 'puzzle') {
        return ($pEdited[1] === '1') ? true : false;
    }
}
function createUserLink($id) {
    global $connection;
    $sql = "SELECT `username`,`permissions`,`online` FROM `users` WHERE id='$id'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $r = $result->fetch_assoc();
        $username = $r['username'];
        $p = str_split($r['permissions']);
        if ($p[0] === '1') {
            $icon = 'fa-shield';
        } else if ($p[1] === '1') {
            $icon = 'fa-puzzle-piece';
        } else {
            $icon = 'fa-circle';
        }
        $user = strtolower($username);
        $online = $r['online'] === '1' ? 'online' : 'offline';
        echo "<a class=\"uilink\" href=\"/member/$user\" userinfo=\"$id\"><i class=\"state fa $icon $online\"></i> $username</a>";
    } else {
        echo "<span>User not found</span>";
    }
}
