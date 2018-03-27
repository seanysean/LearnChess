<?php 
session_start();
include "include/functions.php";
if($l) {
    header('Location: home');
}
function verify($input,$min,$max) {
    if ((strlen($input) > $min) and (strlen($input) < $max)) {
        return true;
    } else {
        return false;
    }
}
if (isset($_POST['username']) and isset($_POST['password'])) {
    $username = preg_replace("/[^a-z1-9\-\_]/i", "",secure($_POST['username']));
    $password = preg_replace("/[^a-z1-9\-\_]/i", "",secure($_POST['password']));
    if(verify($username,3,18) and verify($password,4,21)) {
        $sql = "SELECT username FROM `users` WHERE username='$username'";
        $result = mysqli_query($connection,$sql);
        $duplicate = mysqli_num_rows($result);
        if ($duplicate === 0) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (username,password) VALUES ('$username','$hashed_password')";
            $result = mysqli_query($connection,$sql);
            $getLastIdSQL = "SELECT id FROM `users` ORDER BY id DESC LIMIT 1";
            $getLastIdQ = mysqli_query($connection,$getLastIdSQL);
            $lastID = $getLastIdQ->fetch_assoc()['id'];
            $lowerUsername = strtolower($username);
            $memberProfile = fopen("member/$lowerUsername.php",'x');
            $pageCode = "<?php
\$account = Array('username'=>'$username','id'=>'$lastID');
require \"../templates/profile.php\";";
            fwrite($memberProfile,$pageCode);
            fclose($memberProfile);
            if ($result) {
                $smsg = '<p>Account successfully created! Thank you for joining, you can now <a href="/login">Log in</a>.</p>';
            }
        } else {
            $fmsg = '<p>The username already exists. Try again.</p>';
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
                            <a class="button nocolor" href="/login">
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
                            <input type="text" name="username" minlength="4" id="username" maxlength="17" pattern="[a-zA-Z1-9_-]{4,17}" title="4 to 17 Characters. Allowed characters: 1-9a-z_-" spellcheck="false" autocomplete="off"<?php if(isset($_GET['username'])) { echo " value=\"".$_GET['username']."\""; } ?> autofocus required>
                            <label for="username">Username</label>
                            <span class="line"></span>
                        </div>
                        <p id="usernameResponse" class="input-response"></p>
                        <div class="input-container">
                            <input type="password" name="password" minlength="5" id="password" maxlength="20" pattern="[a-zA-Z1-9_-]{5,20}" title="5 to 20 Characters. Allowed characters: 1-9a-z_-" required>
                            <label for="password">Password</label>
                            <span class="line"></span>
                        </div>
                        <p id="passwordResponse" class="input-response"></p>
                        <p>Allowed username and password characters are letters, numbers, dashes, and underscores. Anything else will be removed. We recommend you use a password you don't use anywhere else.</p>
                        <p>Example valid username: Agood_user-name1</p>
                        <button class="button blue" type="submit"><span><i class="fa fa-check"></i> Register</span></button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <footer>
            <?php include_once "./include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
        <script>
        function valid(text,min,max,inputType,msgContainer) {
            let inputEdited = inputType.split('');
            inputEdited[0] = inputEdited[0].toUpperCase();
            inputEdited = inputEdited.join('');
            const el = document.getElementById(msgContainer);
            if ((text.length > min) && (text.length < max)) {
                const removeInvalidChars = text.replace(/[^a-z1-9_-]/gi,'');
                if (text === removeInvalidChars) {
                    if (inputType === 'username') {
                        const xhr = new XMLHttpRequest();
                        const url = `/autocomplete?exists=1&username=${text}`;
                        xhr.responseType = 'json';
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.response.exists === true) {
                                    el.innerText = ' Username already exists';
                                    el.classList = 'input-response invalid';
                                } else {
                                    el.innerText = ` Valid ${inputType}`;
                                    el.classList = 'input-response valid';
                                }
                            }
                        }
                        xhr.open('GET',url);
                        xhr.send();
                    } else {
                        el.innerText = ` Valid ${inputType}`;
                        el.classList = 'input-response valid';
                    }
                } else {
                    el.innerText = ` ${inputEdited} has invalid characters.`;
                    el.classList = 'input-response invalid';
                }
            } else {
                el.innerText = ` ${inputEdited} too long or too short.`;
                el.classList = 'input-response invalid';
            }
        }
        const inputs = {
            username: document.getElementById('username'),
            password: document.getElementById('password')
        }
        if (inputs.username && inputs.password) {
            inputs.username.addEventListener('keyup',()=>{
                valid(inputs.username.value,3,18,'username','usernameResponse');
            });
            inputs.password.addEventListener('keyup',()=>{
                valid(inputs.password.value,5,21,'password','passwordResponse');
            });
        }
        </script>
    </body>
</html>
