<?php
session_start();
include "../include/functions.php";
include "sidebar.php";
if (!$l) {
    header('Location: /');
}
if(isset($_POST['password'])) {
    $password = secure($_POST['password']);
    $id= $_SESSION['userid'];
    $sql = "SELECT * FROM `users` WHERE id='$id'";
    $result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
    $db_password = $result->fetch_assoc()['password'];
    if (password_verify($password,$db_password)) {
        $sql = "UPDATE `users` SET active='0' WHERE id='$id'";
        $result = mysqli_query($connection,$sql);
        if (!$result) {
            $msg = '<p>Something went wrong...</p>';
        } else {
            header('Location: /logout');
        }
    } else {
        $msg = '<p>Your password was incorrect';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Account settings • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="../css/settings.css" type="text/css" rel="stylesheet">
        <link href="../css/material.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="left-area">
                <?php echo sidebar(3) ?>
            </div>
            <div class="main right">
                <div class="block">
                    <h1 class="block-title"><i class="fa fa-gear"></i> Close account</h1>
                    <?php if(isset($msg)) { echo $msg; } ?>
                    <p>You will not be able to undo this!</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="input-container">
                            <input type="password" name="password" id="password" required>
                            <label for="password">Enter password</label>
                            <span class="line"></span>
                        </div>
                        <button type="submit" class="button red"><span><i class="fa fa-close"></i> Close my account</span></button>
                        <a class="cancel" href="/member/<?php echo strtolower($_SESSION['username']) ?>">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="../js/global.js"></script>
    </body>
</html>