<?php session_start();
include "../include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Thanks • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <meta name="description" content="Learn about the contributors to LearnChess, a free chess website with no premium accounts and no ads.">
        <link href="css/page.css" type="text/css" rel="stylesheet">
        <style>
        .github {
            color: #545454;
        }
        body.dark .github {
            color: #999;
        }
        .github:hover {
            background: #545454 !important;
        }
        </style>
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <h1 class="block-title">Thanks to</h1>
                    <ul>
                        <li><a href="https://github.com/reallinfo" target="_blank" rel="nofollow">@realinfo</a> for the logo</li>
                    </ul>
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
