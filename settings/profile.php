<?php
session_start();
include "../include/functions.php";
include "sidebar.php";
if(!$l) {
    header('Location: /');
}
$userID = $_SESSION['userid'];
$sql = "SELECT `name`,`lichess`,`about`,`chesscom` FROM `users` WHERE id='$userID'";
$result = mysqli_query($connection,$sql) or die('Something went wrong.');
if ($result) {
    $res = $result->fetch_assoc();
    $db_name = $res['name'];
    $db_lichess = $res['lichess'];
    $db_chesscom = $res['chesscom'];
    $db_about = $res['about'];
}
if(isset($_POST['name']) or isset($_POST['lichess']) or isset($_POST['about']) or isset($_POST['chesscom'])) {
    $name = secure($_POST['name']);
    $lichess = secure($_POST['lichess']);
    $chesscom = secure($_POST['chesscom']);
    $about = secure($_POST['about'],true);
    $sql = "UPDATE `users` SET name='$name',lichess='$lichess',chesscom='$chesscom',about='$about' WHERE id='$userID'";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $username = strtolower($_SESSION['username']);
        $msg = '<p><a href="/member/'.$username.'">Your profile</a> has been updated!</p>';
    } else {
        $msg = "<p>Something went wrong...</p>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit profile • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="../css/material.css" type="text/css" rel="stylesheet">
        <link href="../css/settings.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="left-area">
            <?php echo sidebar(1) ?>
            </div>
            <div class="main right">
                <div class="block">
                    <h1 class="block-title"><span class="fa fa-edit"></span> Profile settings</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <?php if(isset($msg)) {
                            echo $msg;
                        } else { ?>
                        <p><a href="/member/<?php echo strtolower($_SESSION['username']) ?>">View your profile</a></p>
                        <?php } ?>
                        <div class="input-line">
                            <div class="input-container third">
                                <input name="name" type="text" id="name" <?php if (isset($name)) { echo "value=\"$name\""; } else if(isset($db_name)) { echo "value=\"$db_name\""; } ?>>
                                <label for="name">Name</label>
                                <span class="line"></span>
                            </div>
                            <div class="input-container third">
                                <input name="lichess" type="text" id="lichess" spellcheck="false" <?php if (isset($lichess)) { echo "value=\"$lichess\""; } else if(isset($db_lichess)) { echo "value=\"$db_lichess\""; } ?>>
                                <label for="lichess">Lichess Username</label>
                                <span class="line"></span>
                            </div>
                            <div class="input-container third">
                                <input name="chesscom" type="text" id="chesscom" spellcheck="false" <?php if (isset($chesscom)) { echo "value=\"$chesscom\""; } else if(isset($db_chesscom)) { echo "value=\"$db_chesscom\""; } ?>>
                                <label for="chesscom">Chess.com Username</label>
                                <span class="line"></span>
                            </div>
                        </div>
                        <div class="input-container">
                            <textarea name="about" id="about" rows="5"><?php if(isset($about)) {
                                $about = str_replace('<br />','',$about); 
                                echo $about; 
                            } else if (isset($db_about)) {
                                $db_about = str_replace('<br />','',$db_about); 
                                echo $db_about;
                            } ?></textarea>
                            <label for="about">Biography</label>
                            <span class="line"></span>
                        </div>
                        <button class="button blue" type="submit"><span><i class="fa fa-check"></i> Update profile</span></button>
                    </form>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="../js/global.js"></script>
    </body>
</html>