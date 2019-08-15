<?php
session_start();
include "../../include/functions.php";
include "sidebar.php";
if(!$l or !isAllowed('puzzle')) {
    header('Location: /');
} else if (isset($_POST['review'])) {
    $review = secure($_POST['review']);
    $r = explode(' ',$review);
    $pID = $r[1];
    $authorID = secure($_POST['authorID']);
    if ($r[0] === 'accept') {
        $pgn = secure($_POST['pgn']);
        $fen = secure($_POST['fen']);
        $explain = secure($_POST['explain']);
        $sql1 = "INSERT INTO `puzzles_approved` (fen,pgn,author_id,explanation) VALUES ('$fen','$pgn','$authorID','$explain');";
        $sql2 = "DELETE FROM `puzzles_to_review` WHERE id='$pID'";
        $result1 = mysqli_query($connection,$sql1);
        $result2 = mysqli_query($connection,$sql2);
        if ($result1 and $result2) {
            $sql3 = "SELECT id FROM `puzzles_approved` ORDER BY id DESC LIMIT 1";
            $result3 = mysqli_query($connection,$sql3);
            $newID = $result3->fetch_assoc()['id'];
            $newPuzzleFile = fopen("view/$newID.php",'x');
            fwrite($newPuzzleFile,"<?php
\$pID = $newID;
include '../../../templates/puzzle.php';");
            fclose($newPuzzleFile);
            $msg = "<p>Puzzle <a href=\"view/$newID\">$newID</a> accepted.</p>";
            createNotification('fa-puzzle-piece',$authorID,'Your puzzle was accepted!',"/puzzles/view/$newID");
        } else {
            $msg = "<p>Something went really wrong.</p>";
        }
    } else if ($r[0] === 'delete') {
        $sql = "DELETE FROM `puzzles_to_review` WHERE id='$pID'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $msg = "<p>Puzzle $pID deleted.</p>";
            createNotification('fa-puzzle-piece',$authorID,'Your puzzle was declined.');
        } else {
            $msg = "<p>Something went really wrong.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Review puzzles • LearnChess</title>
        <?php include_once "../../include/head.php" ?>
        <link href="../css/puzzles.css" rel="stylesheet" type="text/css">
        <link href="../css/chessground.css" rel="stylesheet" type="text/css">
        <link href="../css/material.css" type="text/css" rel="stylesheet">
        <link href="../css/popup.css" type="text/css" rel="stylesheet">
    <head>
    <body<?php include_once "../../include/attributes.php" ?>>
        <div class="top">
        <?php include_once "../../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="left-area"><?php sidebar(1) ?></div>
            <div class="main right">
                <div class="block">
                    <h1 class="block-title"><span class="fa fa-dashboard"></span> Review puzzles</h1>
                    <?php if(isset($msg)) {
                        echo $msg;
                    }
                    $sql = "SELECT * FROM `puzzles_to_review` ORDER BY id DESC";
                    $result = mysqli_query($connection,$sql);
                    if (mysqli_num_rows($result) > 0) { ?>
                    <table class="puzzles-to-review">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Author</th>
                                <th>Position</th>
                                <th>PGN</th>
                                <th>Explanation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                            $authorID = $row['author_id'];
                            $id = $row['id'];
                            $fen = $row['fen'];
                            $pgn = $row['pgn'];
                            $explain = $row['explanation'];
                            ?>
                            <tr>
                                <td><?php echo $id ?></td>
                                <td><?php echo createUserLink($authorID) ?></td>
                                <td><span><a class="fen" target="_blank" powertip='{"type":"position","value":"<?php echo $fen?>"}' href="https://lichess.org/analysis/standard/<?php echo $fen ?>"><?php echo 'Analyze on lichess' ?> <i class="fa fa-external-link"></i></a></span></td>
                                <td>
                                    <span class="pgn" data-id="<?php echo $id ?>" data-type="pgn"><?php echo $pgn ?></span>
                                    <span class="fa fa-pencil edit" data-type="pgn" data-puzzle="<?php echo $id ?>" data-value="<?php echo $pgn ?>"></span>
                                </td>
                                <td>
                                    <span data-id="<?php echo $id ?>" data-type="explain">
                                    <?php if ($explain) { 
                                        echo $explain;
                                    } else {
                                        echo '<span class="no-explain"><i class="fa fa-close"></i> No explanation given</span>'; 
                                    } ?>
                                    </span>
                                    <span class="fa fa-pencil edit" data-type="explain" data-puzzle="<?php echo $id ?>" data-value="<?php if ($explain) {
                                        echo $explain; 
                                    } ?>">
                                    </span>
                                </td>
                                <td><span class="choice">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                        <input type="hidden" value="accept <?php echo $id ?>" name="review">
                                        <input type="hidden" value="<?php echo $authorID ?>" name="authorID">
                                        <input type="hidden" value="<?php echo $fen ?>" name="fen">
                                        <input type="hidden" value="<?php echo $pgn ?>" name="pgn" data-id="<?php echo $id ?>" data-type="pgn">
                                        <input type="hidden" value="<?php echo $explain ?>" name="explain" data-id="<?php echo $id ?>" data-type="explain">
                                        <span class="hint-text-center" data-hint="Approve puzzle">
                                            <a data-delete="0" class="flat-button form-submit">
                                                <span><i class="fa fa-check"></i></span>
                                            </a>
                                        </span>
                                    </form>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                        <input type="hidden" value="delete <?php echo $id ?>" name="review">
                                        <input type="hidden" value="<?php echo $authorID ?>" name="authorID">
                                        <span class="hint-text-center" data-hint="Delete puzzle">
                                            <a data-delete="1" class="flat-button form-submit">
                                                <span><i class="fa fa-close"></i></span>
                                            </a>
                                        </span>
                                    </form>
                                </span></td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<p class=\"nothing-to-see\">No puzzles for review</p>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <footer><?php include_once "../../include/footer.php" ?></footer>
        <script src="../js/global.js"></script>
        <script src="../js/loadposition.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/popup.js"></script>
        <script src="../js/review-puzzles.js"></script>
    </body>
</html>
