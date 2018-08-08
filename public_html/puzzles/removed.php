<?php
session_start();
include "../../include/functions.php";
include "sidebar.php";
if(!$l or !isAllowed('puzzle')) {
    header('Location: /');
} else { ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Removed puzzles • LearnChess</title>
        <?php include_once "../../include/head.php" ?>
        <link href="../css/puzzles.css" rel="stylesheet" type="text/css">
        <link href="../css/chessground.css" rel="stylesheet" type="text/css">
    </head>
    <body<?php include_once "../../include/attributes.php" ?>>
        <div class="top"><?php include_once "../../include/topbar.php" ?></div>
        <div class="page">
            <div class="left-area"><?php sidebar(2) ?></div>
            <div class="main right">
                <div class="block">
                    <h1 class="block-title"><span class="fa fa-close"></span> Removed puzzles</h1>
                    <?php
                    $sql = "SELECT fen,id,author_id FROM `puzzles_approved` WHERE removed='1' ORDER BY id DESC LIMIT 18";
                    $result = mysqli_query($connection,$sql);
                    if (mysqli_num_rows($result) > 0) {
                        echo "<div class=\"accepted-puzzles\">";
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                            $fen = $row['fen'];
                            $pID = $row['id'];
                            $authorID = $row['author_id']; ?>
                            <div class="board-container">
                                <a href="<?php echo "view/$pID" ?>" class="board" data-fen="<?php echo $fen ?>"></a>
                                <div class="credits">Created by<br /><?php echo createUserLink($authorID) ?></div>
                            </div>
                        <?php }
                        echo "</div>";
                    } else {
                        echo "<p class=\"nothing-to-see lower\">No removed puzzles.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <footer><?php include_once "../../include/footer.php" ?></footer>
        <script src="../js/global.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/loadposition.js"></script>
    </body>
</html>
<?php } ?>
