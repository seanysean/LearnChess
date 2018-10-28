<?php 
session_start();
include "../include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $pageTitle ?> • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <meta name="description" content="<?php echo $metaDescription ?>">
        <link href="css/page.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <?php echo $pageContent ?>
                </div>
            </div>
            <div class="right-area">
                <div class="block">
                    <h1 class="block-title">Links</h1>
                    <a href="https://github.com/seanysean/LearnChess" rel="nofollow" target="_blank" class="github"><i class="fa fa-github"></i> GitHub</a>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="js/global.js"></script>
    </body>
</html>
