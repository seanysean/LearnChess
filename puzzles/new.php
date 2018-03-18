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
    </head>
    <body>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main full">
                <div class="block">
                    <h1 class="block-title center">Create new puzzle</h1>
                    <a href="all">Back to puzzles</a>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <?php if(isset($msg)) { echo $msg; } ?>
                        <div class="input-container">
                            <input name="fen" id="fen" type="text" spellcheck="false" required>
                            <label for="fen">Enter FEN</label>
                            <span class="line"></span>
                        </div>
                        <div class="input-container">
                            <input name="pgn" id="pgn" type="text" spellcheck="false" required>
                            <label for="fen">PGN Moves</label>
                            <span class="line"></span>
                        </div>
                        <button class="button green" type="submit">
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
    </body>
</html>
