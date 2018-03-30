<?php
session_start();
include "../include/functions.php";
if(!$l) {
    header('Location: /login');
}
if(isset($_POST['fen']) and isset($_POST['pgn'])) {
    $fen = secure($_POST['fen']);
    $pgn = secure($_POST['pgn']);
    $authorID = $_SESSION['userid'];
    $sql = "INSERT INTO `puzzles_to_review` (fen,pgn,author_id) VALUES ('$fen','$pgn','$authorID')";
    $result = mysqli_query($connection,$sql);
    if ($result) {
        $msg = '<p>Puzzle created successfully! You will be notified when is is approved or disapproved.</p>';
    } else {
        $msg = '<p>Something went wrong...</p>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create new puzzle • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="../css/material.css" rel="stylesheet" type="text/css">
        <link href="../css/puzzles.css" rel="stylesheet" type="text/css">
        <link href="../css/chessground.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main full">
                <div class="block">
                    <h1 class="block-title center">Create new puzzle</h1>
                    <a href="all"><i class="fa fa-arrow-left"></i> Back to puzzles</a>
                    <div class="editor-container">
                        <div class="spare">
                            <piece class="black king" data-piece="black king"></piece>
                            <piece class="black queen" data-piece="black queen"></piece>
                            <piece class="black rook" data-piece="black rook"></piece>
                            <piece class="black bishop" data-piece="black bishop"></piece>
                            <piece class="black knight" data-piece="black knight"></piece>
                            <piece class="black pawn" data-piece="black pawn"></piece>
                        </div>
                        <div id="cg" class="board"></div>
                        <div class="spare">
                            <piece class="white king" data-piece="white king"></piece>
                            <piece class="white queen" data-piece="white queen"></piece>
                            <piece class="white rook" data-piece="white rook"></piece>
                            <piece class="white bishop" data-piece="white bishop"></piece>
                            <piece class="white knight" data-piece="white knight"></piece>
                            <piece class="white pawn" data-piece="white pawn"></piece>
                        </div>
                        <div class="tools">
                            <span class="btn hint-text-center" data-hint="Flip board"><button id="flip" class="flat-button"><i class="fa fa-retweet"></i></button></span>
                            <span class="btn hint-text-center" data-hint="Analyze on lichess"><a id="analyze" target="_blank" href="https://lichess.org/analysis/rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR" class="flat-button blue"><i class="fa fa-search"></i></a></span>
                            <span class="btn hint-text-center" data-hint="Reset board"><button id="initial" class="flat-button"><i class="fa fa-undo"></i></button></span>
                            <span class="btn hint-text-center" data-hint="White to play" id="color"><button class="flat-button"><i class="fa fa-circle-o"></i></button></span>
                            <span class="btn hint-text-center" data-hint="Clear board"><button id="empty" class="flat-button"><i class="fa fa-trash"></i></button></span>
                            <span class="btn hint-text-center" data-hint="Clear piece selection"><button id="clrSelect" class="flat-button"><i class="fa fa-close"></i></button></span>
                        </div>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <?php if(isset($msg)) { echo $msg; } ?>
                        <div class="input-container">
                            <input name="fen" id="fen" type="text" spellcheck="false" value="rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR" required>
                            <label for="fen">Enter FEN</label>
                            <span class="line"></span>
                        </div>
                        <div class="input-container">
                            <input name="pgn" id="pgn" type="text" spellcheck="false" required>
                            <label for="fen">PGN Moves</label>
                            <span class="line"></span>
                        </div>
                        <button class="button blue" type="submit">
                            <span>
                                <i class="fa fa-check"></i>
                                Create puzzle
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <footer>
            <?php include_once "../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/boardeditor.js"></script>
    </body>
</html>
