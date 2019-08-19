<?php 
session_start();
include "../include/functions.php";
if($l) {
    header('Location: home');
}
if (isset($_POST['username']) and isset($_POST['password'])) {
    $username = preg_replace("/[^a-z1-9\-\_]/i", "",secure($_POST['username']));
    $password = preg_replace("/[^a-z1-9\-\_]/i", "",secure($_POST['password']));
    $sql = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
    $count = mysqli_num_rows($result);
    $loginInfo = $result->fetch_assoc();
    if ($count === 1 and $loginInfo['active'] === '1' and password_verify($password,$loginInfo['password'])) {
        $sql = "UPDATE `users` SET online='1',last_active=CURRENT_TIMESTAMP WHERE username='$username'";
        mysqli_query($connection,$sql);
        $_SESSION = [];
        $_SESSION['username'] = $loginInfo['username'];
        $_SESSION['userid'] = $loginInfo['id'];
        $_SESSION['permissions'] = json_decode($loginInfo['permissions'],true);
        $_SESSION['settings'] = (isset($loginInfo['settings']) ? json_decode($loginInfo['settings'],true) : []);
        $_SESSION['rating'] = $loginInfo['rating'];
        $_SESSION['nextpuzzle'] = $loginInfo['nextpuzzle'];
        header('Location: home');
    } else if ($loginInfo['active'] === '0') {
        $msg = '<p>This account was closed.</p>';
    } else {
        $msg = "<p>Incorrect username or password.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="css/material.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page center">
            <div class="main">
                <div class="block">
                    <h1 class="block-title">Login
                        <span class="alternate">
                            <span class="alt-text">New to LearnChess?</span>
                            <a class="button nocolor" href="/register">
                                <span>
                                    <i class="fa fa-sign-in"></i>
                                    Register
                                </span>
                            </a>
                        </span>
                    </h1>
                    <?php if(isset($msg)) { 
                        echo $msg;
                    } ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="input-container">
                            <input type="text" name="username" minlength="4" id="username" maxlength="17" pattern="[a-zA-Z1-9_-]{4,17}" title="Allowed characters: 1-9a-z_-" spellcheck="false" autofocus required>
                            <label for="username">Username</label>
                            <span class="line"></span>
                        </div>
                        <div class="input-container">
                            <input type="password" name="password" minlength="5" id="password" maxlength="20" pattern="[a-zA-Z1-9_-]{5,20}" title="Allowed characters: 1-9a-z_-" required>
                            <label for="password">Password</label>
                            <span class="line"></span>
                        </div>
                        <p>Welcome back.</p>
                        <button class="button blue" type="submit"><span><i class="fa fa-check"></i> Login</span></button>
                    </form>
                </div>
            </div>
        </div>
        <footer>
            <?php include_once "../include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
    </body>
</html>
