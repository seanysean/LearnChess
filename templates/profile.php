<?php
session_start();
include "../include/functions.php";
$accountid = $account['id'];
$sql = "SELECT `name`,`lichess`,`about`,`online`,`permissions` FROM `users` WHERE id='$accountid'";
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
    $p = str_split($res['permissions']);
    if ($p[0] === '1') {
        $icon = 'fa-shield';
        $hint = ' data-hint="Admin"';
    } else if ($p[1] === '1') {
        $icon = 'fa-puzzle-piece';
        $hint = ' data-hint="Puzzle reviewer"';
    } else {
        $icon = 'fa-circle';
        $hint = '';
    }
    $online = $res['online'];
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
                        <span<?php echo $hint ?>>
                            <span class="fa <?php echo $icon ?> state<?php echo $online === '1' ? ' online' : ' offline' ?>"></span>
                        </span>
                        <?php echo $account['username'] ?>
                        <?php if($l and ($accountid === $_SESSION['userid'] or isAllowed('admin'))) { ?>
                        <span class="alternate">
                            <?php if($accountid === $_SESSION['userid']) { ?>
                            <a href="/settings/profile" class="button blue"><span><i class="fa fa-pencil"></i> Edit profile</span></a>
                            <?php } else if (isAllowed('admin')) { 
                                $usrnm = $account['username']; ?>
                                <a href="/admin/search?username=<?php echo $usrnm ?>" class="button blue"><span><i class="fa fa-shield"></i> View and edit info</span></a>
                            <?php } ?>
                        </span>
                        <?php } ?>
                    </h1>
                    <?php if(isset($thisUsersName)) { ?>
                    <p class="name"><?php echo $thisUsersName ?></p>
                    <?php } ?>
                    <?php if(strlen($thisUsersLichessProfile) > 0) {
                        echo "<a class=\"lichess\" target=\"_blank\" href=\"https://lichess.org/@/$thisUsersLichessProfile\">View lichess profile <i class=\"fa fa-external-link\"></i></a>";
                    } ?>
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