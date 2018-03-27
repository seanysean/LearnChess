<?php session_start();
include "include/functions.php";
if ($l) {
?>
<!DOCTYPE html>
<html>
    <head>
        <title>LearnChess</title>
        <?php include_once "./include/head.php" ?>
        <link href="css/chessground.css" type="text/css" rel="stylesheet">
        <style>
        #puzzleOfTheDay {
            width: 200px;
            height: 200px;
            transition: 0.25s opacity ease;
        }
        #puzzleOfTheDay:hover {
            opacity: 0.5;
        }
        </style>
    </head>
    <body>
        <div class="top">
            <?php include_once "./include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block"></div>
            </div>
            <div class="right-area">
                <!--<div class="block">
                    <h1 class="block-title center">Puzzle of the day</h1>
                    <a href="puzzles/view/3"><div id="puzzleOfTheDay"></div></a>
                </div>-->
            </div>
        </div>
        <footer>
            <?php include_once "./include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
        <script src="js/chessground.min.js"></script>
        <script src="js/loadposition.js"></script>
        <script>
        loadPosition(document.getElementById('puzzleOfTheDay'),'rnbqkbnr/pppp2pp/5p2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R');
        </script>
    </body>
</html>
<?php } else {
    header('Location: login');
} ?>
