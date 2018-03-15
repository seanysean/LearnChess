<?php
session_start();
require "../include/formsecurity.php";
require "../include/connect.php";
if (!isset($_SESSION['username'])) {
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
        <div id="popup">
            <p class="popup-title">Are you sure?</p>
            <div class="popup-content">
            <p>You will not be able to undo this!</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="input-container">
                        <input type="password" name="password" id="password" required>
                        <label for="password">Enter password</label>
                        <span class="line"></span>
                    </div>
                    <button type="submit" class="button red full"><span><i class="fa fa-close"></i> Close my account</span></button>
                </form>
            </div>
        </div>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="left-area">
                <div class="block links-section">
                    <a href="profile"><i class="fa fa-edit"></i> Profile</a>
                    <a href="account" class="current"><i class="fa fa-gear"></i> Account</a>
                </div>
            </div>
            <div class="main right">
                <div class="block">
                    <h1 class="block-title"><i class="fa fa-gear"></i> Account settings</h1>
                    <?php if(isset($msg)) { echo $msg; } ?>
                    <button class="button red" id="close-account"><span><i class="fa fa-close"></i> Close Account</span></button>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="../js/global.js"></script>
        <script src="./closeAccount.js"></script>
    </body>
</html>