<?php
session_start();
require "include/functions.php";
if(!$l) {
    echo '[ {"message":"-","error":"You are logged out"} ]';
} else {
    $userID = $_SESSION['userid'];
    if (isset($_POST['n'])) {
        $n = secure($_POST['n']);
        $sql = "SELECT `to_id` FROM `notifications` WHERE `id`='$n'";
        $result = mysqli_query($connection,$sql);
        $to_id = $result->fetch_assoc()['to_id'];
        if ($_SESSION['userid'] === $to_id) {
            $sql = "UPDATE `notifications` SET unread='0' WHERE `id`='$n'";
            mysqli_query($connection,$sql);
        }
        header('Location: notifications');
    } else if (isset($_GET['_'])) {
        $sql = "SELECT * FROM `notifications` WHERE `to_id`='$userID' AND `unread`='1' ORDER BY id DESC LIMIT 10";
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
            echo '[ {"message":"-","error":"No new notifications"} ]';
        }
    } else if (isset($_POST['mark-all'])) {
        $sql = "UPDATE `notifications` SET unread='0' WHERE to_id='$userID'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            header('Location: /notifications');
        } else {
            echo "Something went wrong!";
        }
    } else { 
        $sql = "SELECT id FROM `notifications` WHERE to_id='$userID' AND unread='1'";
        $unread_count = mysqli_num_rows(mysqli_query($connection,$sql)); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Notifications • LearnChess</title>
        <?php include "include/head.php" ?>
        <link href="css/notifications.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="top">
        <?php include "include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main center">
                <div class="block">
    <h1 class="block-title center"><i class="fa fa-bell-o"></i> Notifications<?php if ($unread_count) { echo " ($unread_count)"; } ?><?php if ($unread_count) { ?><form class="alternate" action="/notifications" method="post"><input type="hidden" value=" " name="mark-all"><button type="submit" class="flat-button"><i class="fa fa-check"></i> Mark all as read</button></form><?php } ?></h1>
                    <div class="container">
                    <?php
                    $myid = $_SESSION['userid'];
                    $sql = "SELECT * FROM `notifications` WHERE to_id='$myid' ORDER BY id DESC";
                    $result = mysqli_query($connection,$sql);
                    if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) { ?>
                        <div class="notif<?php if($row['unread'] === '0') { echo " read"; }?>"><i class="notif-icon fa <?php echo $row['icon'] ?>"></i> <?php echo $row['message'] ?> <?php if($row['unread'] === '1') { ?><form action="/notifications" method="post"><input type="hidden" name="n" value="<?php echo $row['id'] ?>"><i data-hint="Mark as read" class="hint-text-center fa fa-check mark-read"></i></form><?php } ?></div>
                    <?php } 
                    } else {
                        echo "<p class=\"nothing-to-see\">No notifications</p>";
                    } ?>
                    </div>
                </div>
            </div>
        </div>
        <footer>
        <?php include "include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
        <script src="js/notifications.js"></script>
    </body>
</html>
<?php }
} ?>
