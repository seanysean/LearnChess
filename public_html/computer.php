<?php 
session_start();
include_once "../include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Play Computer • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <meta name="description" content="Play Stockfish, the world's strongest free chess engine, on LearnChess for free. No registration required.">
        <link href="css/chessground.css" type="text/css" rel="stylesheet">
        <link href="css/computer.css" type="text/css" rel="stylesheet">
        <link href="css/popup.css" type="text/css" rel="stylesheet">
        <link href="css/material.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top"><?php include_once "../include/topbar.php" ?></div>
        <div class="page has-header">
            <div class="block">
                <h1 class="block-title center"><i class="fa fa-laptop"></i> Play Computer</h1>
            </div>
            <div class="main">
                <div class="block">
                    <div id="board"></div>
                </div>
            </div>
            <div class="right-area">
                <div class="block">
                    <div class="buttons">
                        <button class="flat-button" id="resign"><span class="inner"><i class="fa fa-flag"></i></span></button>
                        <button class="flat-button" id="takeback"><span class="inner"><i class="fa fa-undo"></i></span></button>
                        <button class="flat-button" id="flip"><span class="inner"><i class="fa fa-retweet"></i></span></button>
                    </div>
                </div>
                <div class="block">
                    <h2 class="block-title">Evaluation <i id="eval-help" class="fa fa-question"></i></h2>   
                    <p><i class="fa fa-laptop"></i> <span id="eval-text">0.00</span></p>
                    <div class="eval-container">
                        <div id="eval-bar"></div>
                    </div>
                </div>
                <div class="block">
                    <h2 class="block-title">Moves</h2>
                    <div id="moves"></div>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="js/global.js"></script>
        <script src="js/chess.min.js"></script>
        <script src="js/chess-functions.js"></script>
        <script src="js/chessground.min.js"></script>
        <script src="js/stockfish.js"></script>
        <script src="js/popup.js"></script>
        <script src="js/computer.js"></script>
    </body>
</html>
