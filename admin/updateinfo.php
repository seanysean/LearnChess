<?php
session_start();
include "../include/functions.php";
if (!$l or !isAllowed('admin')) {
    header('Location: /');
} else if (isset($_POST['id'])) {
    $id = secure($_POST['id']);
    $success = false;
    if(isset($_POST['open'])) {
        $open = secure($_POST['open']);
        $num = strtolower($open) === 'no' ? '0' : '1';
        $sql = "UPDATE `users` SET active='$num' WHERE id='$id'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $success = true;
        }
    } else if(isset($_POST['name'])) {
        $name = secure($_POST['name']);
        $sql = "UPDATE `users` SET `name`='$name' WHERE id='$id'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $success = true;
        }
    } else if(isset($_POST['about'])) {
        $about = secure($_POST['about']);
        $sql = "UPDATE `users` SET about='$about' WHERE id='$id'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $success = true;
        }
    } else if(isset($_POST['lichess'])) {
        $lichess = secure($_POST['lichess']);
        $sql = "UPDATE `users` SET lichess='$lichess' WHERE id='$id'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $success = true;
        }
    } ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Update profile</title>
        <?php include_once "../include/head.php" ?>
    </head>
    <body>
        <div class="top">
        <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main center">
                <div class="block transparent">
                    <p><?php if ($success) {
                        echo "Request completed successfully";
                    } else {
                        echo "Something went wrong...";
                    } ?>
                    </p>
                </div>
            </div>
        </div>
        <footer>
        <?php include_once "../include/footer.php" ?>
        </footer>
    </body>
</html>
<?php } ?>
