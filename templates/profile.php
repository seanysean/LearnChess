<?php
session_start();
include "../include/functions.php";
$accountid = $account['id'];
$sql = "SELECT name,lichess,about FROM `users` WHERE id='$accountid'";
$result = mysqli_query($connection,$sql);
if ($result) {
    $res = $result->fetch_assoc();
    if (isset($res['name'])) {
        $thisUsersName = $res['name'];
    }
    if (isset($res['lichess'])) {
        $thisUsersLichessProfile = $res['lichess'];
    }
    if (isset($res['about'])) {
        $aboutThisUser = $res['about'];
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $account['username'] ?> • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="../css/profile.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top"><?php include_once "../include/topbar.php" ?></div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <h1 class="block-title">
                        <?php echo $account['username'] ?>
                        <?php if($l and $accountid === $_SESSION['userid']) { ?>
                        <span class="alternate">
                            <a href="/settings/profile" class="button blue"><span><i class="fa fa-pencil"></i> Edit profile</span></a>
                        </span>
                        <?php } ?>
                    </h1>
                    <?php if(isset($thisUsersLichessProfile)) {
                        echo "<a href=\"https://lichess.org/@/$thisUsersLichessProfile\">View lichess profile</a>";
                    } ?>
                    <?php if(isset($thisUsersName)) { ?>
                    <p><?php echo $thisUsersName ?></p>
                    <?php } ?>
                    <div class="info-bar">
                        
                    </div>
                    <div class="about"><?php echo $aboutThisUser ?></div>
                </div>
            </div>
            <div class="right-area">
                <div class="block">
                    <h1 class="block-title">Contributions</h1>
                    <p class="nothing-to-see">No contributions made yet</p>
                </div>
            </div>
        </div>
        <script src="../js/global.js"></script>
    </body>
</head>