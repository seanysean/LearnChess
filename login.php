<?php 
session_start();
require "./include/connect.php";
if(isset($_SESSION['username'])) {
    header('Location: home.php');
}
function secure($input) {
    $input = preg_replace("/[^a-z1-9\-\_]/i", "", $input);
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}
if (isset($_POST['username']) and isset($_POST['password'])) {
    $username = secure($_POST['username']);
    $password = secure($_POST['password']);
    $sql = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($connection,$sql) or die(mysqli_error($connection));
    $count = mysqli_num_rows($result);
    $loginInfo = $result->fetch_assoc();
    if ($count === 1 and $loginInfo['active'] === '1' and password_verify($password,$loginInfo['password'])) {
        $_SESSION['username'] = $loginInfo['username'];
        $_SESSION['userid'] = $loginInfo['id'];
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
        <?php include_once "./include/head.php" ?>
        <link href="css/material.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top">
            <?php include_once "./include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main center">
                <div class="block">
                    <h1 class="block-title">Login
                        <span class="alternate">
                            <span class="alt-text">New to LearnChess?</span>
                            <a class="button" href="/register">
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
                            <input type="text" name="username" minlength="4" id="username" maxlength="17" pattern="[a-zA-Z1-9_-]{4,17}" title="Allowed characters: 1-9a-z_-" spellcheck="false" required>
                            <label for="username">Username</label>
                            <i class="line"></i>
                        </div>
                        <div class="input-container">
                            <input type="password" name="password" minlength="5" id="password" maxlength="20" pattern="[a-zA-Z1-9_-]{5,20}" title="Allowed characters: 1-9a-z_-" required>
                            <label for="password">Password</label>
                            <i class="line"></i>
                        </div>
                        <p>Welcome back.</p>
                        <button class="button" type="submit"><span><i class="fa fa-check"></i> Login</span></button>
                    </form>
                </div>
            </div>
        </div>
        <footer>
            <?php include_once "./include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
    </body>
</html>
