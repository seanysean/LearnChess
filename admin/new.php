<?php
session_start();
require "../include/functions.php";
include "sidebar.php";
if (!$l or !isAllowed('admin')) {
    header('Location: /');
} else {
?>
<!DOCTYPE html>
<html>
    <head>
        <title>New members • LearnChess</title>
        <?php include "../include/head.php" ?>
        <link href="/css/admin.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top">
        <?php include "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="left-area">
                <?php sidebar('new'); ?>
            </div>
            <div class="main right">
                <div class="block">
                    <h1 class="block-title center"><i class="fa fa-shield"></i> New users</h1>
                    <?php 
                    $sql = "SELECT `id` FROM `users` ORDER BY `id` DESC LIMIT 10";
                    $result = mysqli_query($connection,$sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                            echo '<div style="margin-top:20px">'.createUserLink($row['id']).'</div>';
                        }
                    } 
                    ?>
                </div>
            </div>
        </div>
        <footer>
        <?php include "../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
    </body>
</html>
<?php } ?>
