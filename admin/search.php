<?php
session_start();
include "../include/functions.php";
if (!$l || !isAllowed('admin')) {
    header('Location: /');
} else if (isset($_GET['username'])) {
    $username = secure($_GET['username']);
    $sql = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($connection,$sql);
    $res = $result->fetch_assoc();
    $id = $res['id'];
    $open = $res['active'] === '1' ? 'Yes' : 'No';
    $dbtime = $res['created'];
    $timestamp = strtotime($dbtime);
    $created = date("M. d Y h:i A", $timestamp);
    $name = $res['name'];
    $about = $res['about'];
    $lichess = $res['lichess'];
    $permissions = $res['permissions'];
} ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Edit profiles • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="/css/material.css" type="text/css" rel="stylesheet">
        <link href="/css/admin.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top">
        <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main center">
                <div class="block">
                    <h1 class="block-title"><i class="fa fa-shield"></i> Admin</h1>
                </div>
                <div class="block transparent">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="search" method="get">
                        <div class="input-container">
                            <input id="username" name="username"<?php if (isset($_GET['username'])) { echo 'value="'.secure($_GET['username']).'"'; } ?> autocomplete="off" spellcheck="false" autofocus>
                            <label for="username">Enter username</label>
                            <div class="autocomplete" id="autocomplete-container">
                                <div class="user"><i class="fa fa-circle state online"></i> Admin</div>
                            </div>
                            <span class="line"></span>
                        </div>
                        <button class="button blue" type="submit"><span><i class="fa fa-shield"></i> Get info</span></button>
                    </form>
                </div>
                <?php if (isset($_GET['username'])) { ?>
                <div class="block">
                    <?php if(!$id) { ?>
                    <p>That account does not exist</p>
                    <?php } else { ?>
                    <h1 class="block-title">Information about <?php echo $username ?></h1>
                    <p><b>Profile:</b> <?php echo createUserLink($id) ?>
                    <p><b>ID:</b> <?php echo $id ?></p>
                    <p><b>Account open:</b> <?php echo $open ?></p>
                    <p><b>Account created:</b> <?php echo $created ?></p>
                    <p><b>Name:</b> <?php echo $name ?></p>
                    <p><b>About:</b> <?php echo $about ?></p>
                    <p><b>Lichess username:</b> <?php echo $lichess ?></p>
                    <p><b>Permissions:</b> <?php echo $permissions ?></p>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <footer>
        <?php include_once "../include/footer.php" ?>
        </footer>
        <script src="/js/global.js"></script>
        <script src="/js/admin.js"></script>
    </body>
</html>
