<?php
session_start();
require "include/functions.php";
header('Content-Type','text/html');
if(isset($_GET['u']) or isset($_GET['username'])) {
    if (isset($_GET['u'])) {
        $uID = secure($_GET['u']);
        $sql = "SELECT * FROM `users` WHERE id='$uID'";
    } else if (isset($_GET['username'])) {
        $username = secure($_GET['username']);
        $sql = "SELECT * FROM `users` WHERE username='$username'";
    }
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $r = $result->fetch_assoc();
        $username = $r['username'];
        if ($username) {
            $closed = $r['active'] === '1' ? false : true;
            $online = $r['online'] === '1' ? 'online' : 'offline';
            $user = strtolower($username);
            $p = str_split($r['permissions']);
            if ($p[0] === '1') {
                $icon = 'fa-shield';
                $hint = 'data-hint="Admin"';
            } else if ($p[1] === '1') {
                $icon = 'fa-puzzle-piece';
                $hint = 'data-hint="Puzzle reviewer"';
            } else {
                $icon = 'fa-circle';
                $hint = '';
            }
            if (isAllowed('admin')) {
                $adminLink = "<div class=\"admin-buttons\"><a class=\"flat-button\" href=\"/admin/search?username=$username\"><span class=\"fa fa-shield\"></span></a></div>";
            } else {
                $adminLink = '';
            }
            $link = $r['lichess'] ? "<a target=\"_blank\" href=\"https://lichess.org/@/".strtolower($r['lichess'])."\">Lichess <i class=\"fa fa-external-link\"></i></a>" : "<a href=\"/member/$user\">Profile</a>";
            if ($closed) {
                echo "<div class=\"ui-top\"><a class=\"uilink\" href=\"/member/$user\"><i class=\"fa fa-circle state $online\"></i> <span>$username</span></a></div><div class=\"padding no-account\">Account closed</div>$adminLink";
            } else {
                echo "<div class=\"ui-top\"><a class=\"uilink\" href=\"/member/$user\"><span $hint><i class=\"fa $icon state $online\"></i></span> <span>$username</span></a></div><div class=\"padding\">$link</div>$adminLink";
            }
        } else {
            echo "<div class=\"padding no-account not-found\">Account not found</p>";
        }
    } else {
        echo "<div class=\"padding no-account\">There was a error</p>";
    }
}
