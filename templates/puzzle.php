<?php
session_start();
include "../../../include/functions.php";
$sql = "SELECT * FROM `puzzles_approved` WHERE id='$pID'";
$result = mysqli_query($connection,$sql);
if ($result) {
    $res = $result->fetch_assoc();
    $pgn = $res['pgn'];
    $fen = $res['fen'];
    $trophies = $res['trophies'];
    $moreThan1 = $trophies > 1 ? "$trophies times":'once';
    $removed = $res['removed'] === '0' ? false : true;
    $authorid = $res['author_id'];
    $author = mysqli_query($connection,"SELECT username FROM `users` WHERE id='$authorid'")->fetch_assoc()['username'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Puzzle <?php echo $pID ?> • LearnChess</title>
        <?php include_once "../../../include/head.php"; ?>
        <meta name="description" content="Solve a chess puzzle created by <?php echo $author ?> on LearnChess.<?php if ($trophies > 0) { echo " This puzzle has been given a trophy $moreThan1."; } ?>">
        <link href="/css/chessground.css" type="text/css" rel="stylesheet">
        <link href="/css/puzzles.css" type="text/css" rel="stylesheet">
        <link href="/css/popup.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../../../include/attributes.php" ?>>
        <div class="top">
            <?php include_once "../../../include/topbar.php"; ?>
        </div>
        <div class="page">
            <div class="block puzzle-header">
                <h1 class="block-title center"><i class="fa fa-puzzle-piece"></i> Puzzle <?php echo $pID ?></h1>
            </div>
            <div class="main<?php if($removed) { echo " center"; } ?>">
                <div class="block">
                <?php if (!$removed) { ?>
                    <div id="chessground"></div>
                <?php } else {
                    $_SESSION['nextpuzzle'] = $pID + 1;
                    $form = '';
                    if (isAllowed('puzzle')) {
                        $form = '<form method="post" action="../remove"><input type="hidden" value="1" name="undo"><input type="hidden" value="'.$pID.'" name="puzzle"><button class="flat-button" type="submit"><i class="fa fa-undo"></i> Bring puzzle back</button>';
                    }
                    echo "<p class=\"nothing-to-see removed\"><i class=\"fa fa-ban\"></i> This puzzle was removed.</p><a class=\"flat-button continue-training transition\" href=\"../next\">Continue practicing</a>$form"; 
                }
                ?>
                </div>
            </div>
            <?php if (!$removed) { ?>
            <div class="right-area">
                <div class="block feedback" id="res-container">
                    <div id="response" class="neutral loading"><div class="loader"></div></div>
                </div>
                <div class="block start-container hidden" id="next">
                    <a href="../next" class="flat-button green full transition"><span><i class="fa fa-check"></i> Next</span></a>
                </div>
                <div class="credits block hidden" id="credits"></div>
                <div class="block copyings hidden" id="copyings">
                    <?php if(isAllowed('puzzle')) { ?>
                    <form action="/puzzles/remove" method="post">
                        <input type="hidden" value="<?php echo $pID ?>" name="puzzle">
                        <button class="flat-button" type="submit"><i class="fa fa-close"></i> Remove puzzle</button>
                    </form>
                    <?php } ?>
                    <h3>Copy URL <span id="copy-1" class="copy-badge"><i class="fa fa-check"></i> Copied</span></h3>
                    <input class="copy-on-click" id="puzzleURL" readonly />
                    <h3>Copy FEN <span id="copy-2" class="copy-badge"><i class="fa fa-check"></i> Copied</span></h3>
                    <input class="copy-on-click" value="<?php echo $fen ?>" readonly />
                </div>
            </div>
            <?php } ?>
        </div>
        <footer>
            <?php include_once "../../../include/footer.php"; ?>
        </footer>
        <?php if (!$removed) { ?>
        <script>
        const fen = '<?php echo $fen ?>',
              pID = '<?php echo $pID ?>',
              author = '<?php echo $author ?>',
              trophies = '<?php echo $trophies ?>';
        </script>
        <?php } ?>
        <script src="/js/global.js"></script>
        <?php if (!$removed) { ?>
        <script src="/js/chess.min.js"></script>
        <script src="/js/chess-functions.js"></script>
        <script src="/js/chessground.min.js"></script>
        <script src="/js/popup.js"></script>
        <script src="/js/puzzles.js"></script>
        <?php } ?>
    </body>
</html>
