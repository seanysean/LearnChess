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
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top"><?php include_once "../include/topbar.php" ?></div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <h1 class="block-title center"><i class="fa fa-laptop"></i> Play Computer</h1>
                </div>
                <div class="block">
                    <div id="board"></div>
                </div>
            </div>
            <div class="right-area">
                <div class="block">
                    <h1 class="block-title center">Moves</h1>
                    <div id="moves"></div>
                    <div class="buttons">
                        <button class="flat-button" id="resign"><span class="inner"><i class="fa fa-flag"></i></span></button>
                        <button class="flat-button" id="flip"><span class="inner"><i class="fa fa-retweet"></i></span></button>
                    </div>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.2/chess.min.js"></script>
        <script src="js/chess-functions.js"></script>
        <script src="js/chessground.min.js"></script>
        <script src="js/stockfish.js"></script>
        <script src="js/popup.js"></script>
        <script src="js/computer.js"></script>
    </body>
</html>
