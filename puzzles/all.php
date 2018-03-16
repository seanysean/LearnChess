<?php
session_start();
include "../include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Approved puzzles • LearnChess</title>
        <?php include_once "../include/head.php" ?>
    </head>
    <body>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main <?php if(!$l) { echo 'center'; } ?>">
                <div class="block">
                    <h1 class="block-title">Approved puzzles<?php if(isAllowed('puzzle')) { ?>
                        <span class="alternate">
                            <a class="button blue" href="review"><span><i class="fa fa-dashboard"></i> Review new puzzles</a>
                        </span>
                    <?php } ?>
                    </h1>
                </div>
            </div>
            <?php if($l) { ?>
            <div class="right-area">
                <div class="block">
                    <h1 class="block-title center">Your puzzles</h1>
                    <a href="new" class="button nocolor full">
                        <span>
                            <i class="fa fa-plus"></i>
                            New puzzle
                        </span>
                    </a>
                    <div class="your-puzzles">
                        <p class="no-contributions">No puzzles submitted</p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <footer>
            <?php include_once "../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
    </body>
</html>
