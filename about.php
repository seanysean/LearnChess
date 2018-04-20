<?php session_start();
include "include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>LearnChess</title>
        <?php include_once "./include/head.php" ?>
    </head>
    <body<?php include_once "./include/attributes.php" ?>>
        <div class="top">
            <?php include_once "./include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block"></div>
            </div>
            <div class="right-area">
                <div class="block"></div>
            </div>
        </div>
        <footer>
            <?php include_once "./include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
    </body>
</html>
