<?php
session_start();
include "../include/functions.php";
if(!$l or !isAllowed('puzzle')) {
    header('Location: all');
} else if (isset($_POST['review'])) {
    $review = secure($_POST['review']);
    $r = explode(' ',$review);
    $pID = $r[1];
    if ($r[0] === 'accept') {
        $authorID = secure($_POST['authorID']);
        $pgn = secure($_POST['pgn']);
        $fen = secure($_POST['fen']);
        $sql1 = "INSERT INTO `puzzles_approved` (fen,pgn,author_id) VALUES ('$fen','$pgn','$authorID');";
        $sql2 = "DELETE FROM `puzzles_to_review` WHERE id='$pID'";
        $result1 = mysqli_query($connection,$sql1);
        $result2 = mysqli_query($connection,$sql2);
        if ($result1 and $result2) {
            $msg = "<p>Puzzle $pID accepted.</p>";
        } else {
            $msg = "<p>Something went really wrong.</p>";
        }
    } else if ($r[0] === 'delete') {
        $sql = "DELETE FROM `puzzles_to_review` WHERE id='$pID'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $msg = "<p>Puzzle $pID deleted.</p>";
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
        <?php include_once "../include/head.php" ?>
        <link href="../css/puzzles.css" rel="stylesheet" type="text/css">
    <head>
    <body>
        <div class="top">
        <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main full">
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
                                <th>FEN</th>
                                <th>PGN</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                            $authorID = $row['author_id'];
                            $getUsername = "SELECT username FROM `users` WHERE id='$authorID' LIMIT 1";
                            $userResult = mysqli_query($connection,$getUsername);
                            $id = $row['id'];
                            $author = $userResult->fetch_assoc()['username'];
                            $fen = $row['fen'];
                            $pgn = $row['pgn'];
                            ?>
                            <tr>
                                <td><?php echo $id ?></td>
                                <td><a class="author" target="_blank" href="/member/<?php echo strtolower($author) ?>"><?php echo $author ?></a></td>
                                <td><a class="fen" target="_blank" href="https://lichess.org/editor/<?php echo $fen ?>"><?php echo $fen ?> <i class="fa fa-external-link"></i></a></td>
                                <td><span class="pgn"><?php echo $pgn ?></span></td>
                                <td><span class="choice">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                        <input type="hidden" value="accept <?php echo $id ?>" name="review">
                                        <input type="hidden" value="<?php echo $authorID ?>" name="authorID">
                                        <input type="hidden" value="<?php echo $fen ?>" name="fen">
                                        <input type="hidden" value="<?php echo $pgn ?>" name="pgn">
                                        <button type="submit" class="button green"><span><i class="fa fa-check"></i></span></button>
                                    </form>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                        <input type="hidden" value="delete <?php echo $id ?>" name="review">
                                        <button type="submit" class="button red"><span><i class="fa fa-close"></i></span></button>
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
        <footer>
        <?php include_once "../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
    </body>
</html>