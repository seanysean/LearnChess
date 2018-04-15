<?php
session_start();
include "../../include/functions.php";
$sql = "SELECT * FROM `puzzles_approved` WHERE id='$pID'";
$result = mysqli_query($connection,$sql);
if ($result) {
    $res = $result->fetch_assoc();
    $pgn = $res['pgn'];
    $fen = $res['fen'];
    $trophies = $res['trophies'];
    $removed = $res['removed'] === '0' ? false : true;
    $authorid = $res['author_id'];
    $author = mysqli_query($connection,"SELECT username FROM `users` WHERE id='$authorid'")->fetch_assoc()['username'];
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
            <div class="main<?php if($removed) { echo " center"; } ?>">
                <div class="block transparent">
                    <h1 class="block-title center"><i class="fa fa-puzzle-piece"></i> Puzzle <?php echo $pID ?></h1>
                    <?php if($removed) {
                        $form = '';
                        if (isAllowed('puzzle')) {
                            $form = '<form method="post" action="../remove"><input type="hidden" value="1" name="undo"><input type="hidden" value="'.$pID.'" name="puzzle"><button class="flat-button" type="submit"><i class="fa fa-undo"></i> Bring puzzle back</button>';
                        }
                        echo "<p class=\"nothing-to-see removed\"><i class=\"fa fa-ban\"></i> This puzzle was removed.</p>$form"; 
                    }
                    ?>
                </div>
                <?php if (!$removed) { ?>
                <div class="block transparent">
                    <div id="chessground"></div>
                </div>
                <?php } ?>
            </div>
            <?php if (!$removed) { ?>
            <div class="right-area">
                <div class="block feedback" id="res-container">
                    <div id="response" class="neutral loading"><div class="loader"></div></div>
                </div>
                <div class="block copyings" id="copyings">
                    <h3>Copy URL</h1>
                    <input id="puzzleURL" />
                    <h3>Copy FEN</h1>
                    <input value="<?php echo $fen ?>" />
                    <?php if(isAllowed('puzzle')) { ?>
                    <form action="/puzzles/remove" method="post">
                        <input type="hidden" value="<?php echo $pID ?>" name="puzzle">
                        <button class="flat-button" type="submit"><i class="fa fa-close"></i> Remove puzzle</button>
                    </form>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <footer>
            <?php include_once "../../include/footer.php"; ?>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chess.js/0.10.2/chess.min.js"></script>
        <script src="/js/chessground.min.js"></script>
        <script src="/js/puzzles.js"></script>
        <?php } ?>
    </body>
</html>
