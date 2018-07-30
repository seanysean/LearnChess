<?php
session_start();
include "../include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Coordinates • LearnChess</title>
        <?php include "../include/head.php" ?>
        <meta name="description" content="Become faster at identifying chess coordinates with this free tool on LearnChess. No registration necessary."
        <link href="../css/coordinates.css" type="text/css" rel="stylesheet">
        <link href="../css/chessground.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top">
        <?php include "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block transparent">
                    <h1 class="block-title center"><i class="fa fa-bullseye"></i> Coordinates</h1>
                </div>
                <div class="block transparent">
                    <div class="board-cont">
                        <div class="overlay" id="overlay"><p class="cont" id="square">Press start</p></div>
                        <div id="board" data-fen="rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w"></div>
                    </div>
                    <div class="timebar"><div class="done" id="time"></div></div>
                </div>
            </div>
            <div class="right-area">
                <div class="block transparent start-card" id="c1">
                    <p>How many coordinates can you find in 30s?</p>
                    <button id="start" class="button blue full"><span><i class="fa fa-check"></i> Start</span></button>
                </div>
                <div class="block transparent response right" id="c2">
                    <p><i class="fa fa-check"></i> Click on the board</p>
                </div>
            </div>
        </div>
        <footer>
        <?php include "../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/loadposition.js"></script>
        <script src="../js/coordinates.js"></script>
    </body>
</html>
