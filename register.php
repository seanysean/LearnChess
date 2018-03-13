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
function verify($input,$min,$max) {
    if ((strlen($input) > $min) and (strlen($input) < $max)) {
        return true;
    } else {
        return false;
    }
}
if (isset($_POST['username']) and isset($_POST['password'])) {
    $username = secure($_POST['username']);
    $password = secure($_POST['password']);
    if(verify($username,3,18) and verify($password,4,21)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (username,password) VALUES ('$username','$hashed_password')";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $smsg = '<p>Account successfully created! Thank you for joining, you can now <a href="/login">Log in</a>.</p>';
        }
    } else {
        $fmsg = '<p>Sorry, your input was not valid. Perhaps your password or username was too long or too short.';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register • LearnChess</title>
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
                    <h1 class="block-title">Register<span class="alternate">
                        <span class="alt-text">Already have a account?</span>
                            <a class="button" href="/login">
                                <span>
                                    <i class="fa fa-sign-in"></i>
                                    Login
                                </span>
                            </a>
                        </span>
                    </h1>
                    <?php if(isset($smsg)) { 
                        echo $smsg;
                    } else { 
                        if (isset($fmsg)) {
                            echo $fmsg;
                        }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="input-container">
                            <input type="text" name="username" minlength="4" id="username" maxlength="17" pattern="[a-zA-Z1-9_-]{4,17}" title="4 to 17 Characters. Allowed characters: 1-9a-z_-" spellcheck="false" required>
                            <label for="username">Username</label>
                            <i class="line"></i>
                        </div>
                        <div class="input-container">
                            <input type="password" name="password" minlength="5" id="password" maxlength="20" pattern="[a-zA-Z1-9_-]{5,20}" title="5 to 20 Characters. Allowed characters: 1-9a-z_-" required>
                            <label for="password">Password</label>
                            <i class="line"></i>
                        </div>
                        <p>Allowed username and password characters are letters, numbers, dashes, and underscores. Anything else will be removed. We recommend you use a password you don't use anywhere else.</p>
                        <p>Example valid username: Agood_user-name1</p>
                        <button class="button" type="submit"><span><i class="fa fa-check"></i> Register</span></button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <footer>
            <?php include_once "./include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
    </body>
</html>
