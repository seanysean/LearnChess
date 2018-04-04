<?php
session_start();
include "include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Recent changes • LearnChess</title>
        <?php include "include/head.php" ?>
        <link href="css/changes.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="top">
        <?php include "include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main center">
                <div class="block">
                    <h1 class="block-title center"><i class="fa fa-wrench"></i> Recent changes</h1>
                    <div id="changes-container"></div>
                </div>
            </div>
        </div>
        <footer>
        <?php include "include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
        <script src="js/changes.js"></script>
    </body>
</html>
