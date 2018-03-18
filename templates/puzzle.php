<?php
session_start();
include "../../include/functions.php";
$sql = "SELECT * FROM `puzzles_approved` WHERE id='$pID'";
$result = mysqli_query($connection,$sql);
if ($result) {
    $res = $result->fetch_assoc();
    $pgn = $res['pgn'];
    $fen = $res['fen'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Puzzle <?php echo $pID ?> • LearnChess</title>
        <?php include_once "../../include/head.php"; ?>
        <link href="/css/chessground.css" type="text/css" rel="stylesheet">
        <link href="/css/puzzles.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top">
            <?php include_once "../../include/topbar.php"; ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <h1 class="block-title center"><i class="fa fa-puzzle-piece"></i> Puzzle <?php echo $pID ?></h1>
                </div>
                <div class="block transparent">
                    <div id="chessground"></div>
                </div>
            </div>
            <div class="right-area"></div>
        </div>
        <footer>
            <?php include_once "../../include/footer.php"; ?>
        </footer>
        <script>
        const fen = '<?php echo $fen ?>';
        </script>
        <script src="/js/chessground.min.js"></script>
        <script src="/js/puzzles.js"></script>
    </body>
</html>
