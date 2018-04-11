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
        <link href="css/home.css" type="text/css" rel="stylesheet">
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
                <div class="block">
                    <h1 class="block-title">What's new?</h1>
                    <div id="changes-cont"></div>
                    <a href="changes" class="changes-link"><i class="fa fa-wrench"></i> View all changes</a>
                </div>
            </div>
            <div class="right-area">
                <!--<div class="block">
                    <h1 class="block-title center">Puzzle of the day</h1>
                    <a href="puzzles/view/3"><div id="puzzleOfTheDay"></div></a>
                </div>-->
                <div class="block">
                    <h1 class="block-title center"><?php echo $_SESSION['username'] ?></h1>
                </div>
                <div class="block do-stuff no-padding">
                    <a href="/puzzles/new"><i class="fa fa-puzzle-piece"></i> Submit a puzzle</a>
                    <a href="/settings/profile"><i class="fa fa-edit"></i> Edit profile</a>
                </div>
            </div>
        </div>
        <footer>
            <?php include_once "./include/footer.php" ?>
        </footer>
        <script src="js/global.js"></script>
        <script src="js/changeslist.js"></script>
        <script src="js/home.js"></script>
        <script src="js/chessground.min.js"></script>
        <script src="js/loadposition.js"></script>
        <script>
        //loadPosition(document.getElementById('puzzleOfTheDay'),'rnbqkbnr/pppp2pp/5p2/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R');
        </script>
    </body>
</html>
<?php } else {
    header('Location: login');
} ?>
