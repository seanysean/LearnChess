<?php
session_start();
include "../../include/functions.php";
include "sidebar.php";
if (!$l) {
    header('Location: /');
} else {
    function verify($input,$min,$max) {
        if ((strlen($input) > $min) and (strlen($input) < $max)) {
            return true;
        } else {
            return false;
        }
    }
    if(isset($_POST['current_password']) and isset($_POST['new_password1']) and isset($_POST['new_password2'])) {
        $cur = secure($_POST['current_password']);
        $new1 = secure($_POST['new_password1']);
        $new2 = secure($_POST['new_password2']);
        $userid = $_SESSION['userid'];
        $sql = "SELECT `password` FROM `users` WHERE id='$userid'";
        $result = mysqli_query($connection,$sql);
        if (password_verify($cur,$result->fetch_assoc()['password'])) {
            $valid = preg_replace("/[^a-z1-9\-\_]/i", "",$new1);
            if (verify($new1,4,21)) {
                if ($new1 === $valid) {
                    if ($new1 === $new2) {
                        $hashed = password_hash($new1, PASSWORD_DEFAULT);
                        $result = mysqli_query($connection,"UPDATE `users` SET `password`='$hashed'");
                        if ($result) {
                            $msg = 'Password changed successfully';
                        } else {
                            $msg = 'Something went wrong.';
                        }
                    } else {
                        $msg = 'New passwords don\'t match'; 
                    }
                } else {
                    $msg = 'Invalid characters used. You can only use letters, numbers, dashes and underscores';
                }
            } else {
                $msg = 'Your password is too long or too short';
            }
        } else {
            $msg = "Incorrect password";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Change password • LearnChess</title>
        <?php include "../../include/head.php" ?>
        <link href="../css/material.css" type="text/css" rel="stylesheet">
        <link href="../css/settings.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../../include/attributes.php" ?>>
        <div class="top">
        <?php include "../../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="left-area"><?php echo sidebar(3) ?></div>
            <div class="main">
                <div class="block">
                    <h1 class="block-title"><i class="fa fa-key"></i> Change password</h1>
                    <?php if (isset($msg)) { echo "<p>$msg</p>"; } ?>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                        <div class="input-line">
                            <div class="input-container">
                                <input name="current_password" type="password" id="p1" autofocus required>
                                <label for="p1">Current password</label>
                                <span class="line"></span>
                            </div>
                        </div>
                        <div class="input-container">
                            <input name="new_password1" class="check-is-valid" type="password" id="p2" pattern="[a-zA-Z1-9_-]{0,21}" required>
                            <label for="p2">New password</label>
                            <span class="line"></span>
                        </div>
                        <p id="passwordResponse" class="input-response"></p>
                        <div class="input-container">
                            <input name="new_password2" class="check-is-valid" type="password" id="p3" pattern="[a-zA-Z1-9_-]{0,21}" required>
                            <label for="p3">New password (again)</label>
                            <span class="line"></span>
                        </div>
                        <p id="passwordResponse2" class="input-response"></p>
                        <button class="button blue" type="submit"><span><i class="fa fa-check"></i> Update password</button>
                    </form>
                </div>
            </div>
        </div>
        <footer>
        <?php include "../../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
        <script src="../js/changepassword.js"></script>
        <script src="../js/material.js"></script>
    </body>
</html>
<?php } ?>
