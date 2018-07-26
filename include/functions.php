<?php
include "connect.php";
#l is true if user is logged in.
$l = isset($_SESSION['username']);

if ($l) {
    // Is this too much? Checking the database every page load? Maybe it could be done with session variables, checking every 10 or something.
    $currentSessionID = $_SESSION['userid'];
    $updateActivitySQL = "UPDATE `users` SET last_active=CURRENT_TIMESTAMP WHERE id='$currentSessionID'";
    mysqli_query($connection,$updateActivitySQL);
    $closedAccountQuery = "SELECT active FROM `users` WHERE id='$currentSessionID'";
    $closedAccountResult = mysqli_query($connection,$closedAccountQuery);
    if ($closedAccountResult->fetch_assoc()['active'] === '0') {
        header('Location: /logout');
    }
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
function createNotification($icon,$to,$message,$url="") {
    global $connection;
    $sql = "INSERT INTO `notifications` (`icon`,`to_id`,`message`,`url`) VALUES ('$icon','$to','$message','$url')";
    mysqli_query($connection,$sql);
}
function createUserLink($id,$r=false) {
    # $r is true if the HTML should be returned instead of echoed.
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
        $res = "<a class=\"uilink\" href=\"/member/$user\" userinfo=\"$id\"><i class=\"state fa $icon $online\"></i> <span class=\"uilink-username\">$username</span></a>";
        if (!$r) {
            echo $res;
        } else {
            return $res;
        }
    } else {
        $res = "<span>User not found</span>";
        if ($r) {
            return $res;
        } else {
            echo $res;
        }
    }
}
