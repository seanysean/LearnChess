<?php
session_start();
include "../../include/functions.php";
include "../../include/config.php";
$sql = "SELECT id FROM `puzzles_to_review`";
$result = mysqli_query($connection,$sql);
if ($result) {
    $unreviewedCount = mysqli_num_rows($result);
}
function generatePuzzlePositions($sql) { // Why am I using camel case... too much JS I guess.
    global $connection;
    $result = mysqli_query($connection,$sql);
    $boards = '';
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            $fen = $row['fen'];
            $pID = $row['id'];
            $trophies = $row['trophies'];
            $authorID = $row['author_id'];
            $authorLink = createUserLink($authorID);
            $boards .= "<div class=\"board-container\">
                <a href=\"view/$pID\" class=\"board\" data-fen=\"$fen\"></a>
                <div class=\"credits\">$trophies <i class=\"fa fa-trophy\"></i><br />Created by<br />$authorLink</div>
            </div>";
        }
    } else {
        return false;
    }
    return $boards;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Approved puzzles • LearnChess</title>
        <?php include_once "../../include/head.php" ?>
        <meta name="description" content="Improve your chess skills by solving chess tactics created by the community. It's 100% free, no ads, and open source. No registration required.">
        <link href="../css/puzzles.css" rel="stylesheet" type="text/css">
        <link href="../css/chessground.css" rel="stylesheet" type="text/css">
    </head>
    <body<?php include_once "../../include/attributes.php" ?>>
        <div class="top">
            <?php include_once "../../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <h1 class="block-title">Approved puzzles<?php if($l and isAllowed('puzzle')) { ?>
                        <span class="alternate" <?php if($unreviewedCount > 0) { echo "data-hint=\"There are $unreviewedCount unreviewed puzzles\""; } else { echo 'data-hint="There are no more puzzles to review"'; } ?>>
                            <a class="button <?php if($unreviewedCount > 0) { echo "blue"; } else { echo "disabled"; } ?>" <?php if($unreviewedCount > 0) { echo "href=\"review\""; } ?>><span><i class="fa fa-dashboard"></i> Review new puzzles <?php if($unreviewedCount > 0) { echo "($unreviewedCount)"; } ?></a>
                        </span>
                    <?php } ?>
                    </h1>
                    <div class="accepted-puzzles">
                    <h2>Recent puzzles</h2>
                    <?php
                    $sql = "SELECT * FROM `puzzles_approved` WHERE removed='0' ORDER BY id DESC LIMIT 6";
                    echo generatePuzzlePositions($sql);
                    ?>
                    <h2>Top puzzles</h2>
                    <?php
                    $sql = "SELECT * FROM `puzzles_approved` WHERE removed='0' ORDER BY trophies DESC LIMIT 6";
                    $boards = generatePuzzlePositions($sql);
                    $result = mysqli_query($connection,$sql);
                    if ($boards) {
                        echo $boards;
                    } else {
                        echo "<p class=\"nothing-to-see lower center\">No approved puzzles. <a href=\"new\">Why not make one?</a></p>";
                    } ?>
                    </div>
                </div>
            </div>
            <div class="right-area">
                <div class="block start-container">
                    <a href="next" class="flat-button blue full transition"><span><i class="fa fa-check"></i> Start</span></a>
                </div>
                <div class="block">
                <?php if($l) { ?>
                    <h1 class="block-title">Your puzzles <span class="alternate" data-hint="Create new puzzle"><a href="new" class="button blue"><span><i class="fa fa-plus"></i></span></a></span></h1>
                    <div class="your-puzzles">
                        <?php
                        $myID = $_SESSION['userid'];
                        $sql = "SELECT id FROM `puzzles_to_review` WHERE author_id='$myID'";
                        $result = mysqli_query($connection,$sql);
                        if (mysqli_num_rows($result) > 0) {
                            $rows = mysqli_num_rows($result);
                            $s = 's';
                            if ($rows === 1) {
                                $s = '';
                            }
                            echo "<h3>$rows puzzle$s in review</h3>";
                        } else { ?>
                        <p class="nothing-to-see">No puzzles in review</p>
                        <?php } 
                        $sql2 = "SELECT id FROM `puzzles_approved` WHERE author_id='$myID' AND removed='0' ORDER BY id DESC";
                        $result2 = mysqli_query($connection,$sql2);
                        if (mysqli_num_rows($result2) > 0) {
                            $rows = mysqli_num_rows($result2);
                            $s = 's';
                            if ($rows === 1) {
                                $s = '';
                            }
                            echo "<h3>$rows approved puzzle$s</h3>";
                            while($row = mysqli_fetch_array($result2,MYSQLI_ASSOC)) {
                                $puzzleID = $row['id'];
                                ?>
                                <a href="view/<?php echo $puzzleID ?>" class="puzzle">Puzzle <?php echo $puzzleID ?></a>
                        <?php }
                        } else { ?>
                        <p class="nothing-to-see">No approved puzzles</p>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <h1 class="block-title">Contribute</h1>
                    <p><a href="/register">Register</a> to start creating puzzles!</p>
                <?php } ?>
                </div>
                <?php if ($devMode) { // Waiting for more users before bringing out the Leaderboard?><div class="block">
                    <h1 class="block-title">Leaderboard</h1>
                    <ol class="leaderboard">
                    <?php $sql = "SELECT rating,id FROM `users` WHERE NOT active='0' ORDER BY rating DESC LIMIT 5";
                        $result = mysqli_query($connection,$sql);
                        $n = 1;
                        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) { 
                            $lId = $row['id'];
                            $lRating = $row['rating'];
                            $uiLink = createUserLink($lId,true); ?>
                        <li><?php echo '<span><span class="rank">' . $n++ . '</span>' . $uiLink . '</span><span class="rating">' . round($lRating) . '</span>'; ?></li>
                        <?php } ?>
                    </ol>
                </div>
                <?php } ?>
            </div>
        </div>
        <footer>
            <?php include_once "../../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/loadposition.js"></script>
    </body>
</html>
