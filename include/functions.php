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
    # Valid $types: 'puzzle'.
    $pEdited = str_split($_SESSION['permissions']);
    if ($type === "puzzle") {
        return ($pEdited[1] === '1') ? true : false;
    }
}
function createUserLink($id) {
    global $connection;
    $sql = "SELECT * FROM `users` WHERE id='$id'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $r = $result->fetch_assoc();
        $username = $r['username'];
        $user = strtolower($username);
        $online = $r['online'] === '1' ? 'online' : 'offline';
        echo "<a class=\"uilink\" href=\"/member/$user\" userinfo=\"$id\"><i class=\"state fa fa-circle $online\"></i> $username</a>";
    } else {
        echo "<span>User not found</span>";
    }
}
