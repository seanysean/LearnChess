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
        $sql = "UPDATE `puzzles` SET approved='1',reviewed='1' WHERE id='$pID'";
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $msg = "<p>Puzzle $pID accepted.</p>";
        } else {
            $msg = "<p>Something went really wrong.</p>";
        }
    } else if ($r[0] === 'delete') {
        $sql = "UPDATE `puzzles` SET reviewed='1' WHERE id='$pID'";
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
                    } ?>
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
                            $sql = "SELECT * FROM `puzzles` WHERE approved='0' AND reviewed='0' ORDER BY id DESC";
                            $result = mysqli_query($connection,$sql);
                            if (mysqli_num_rows($result) > 0) {
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
                                echo "<p>No puzzles for review</p>";
                            } ?>
                            <!--<tr>
                                <td>2</td>
                                <td><a class="author" href="#">Seanysean</a></td>
                                <td><a class="fen" href="#">rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR</a></td>
                                <td><span class="pgn">1. h4 h6 2. Bh1 Rb7 3. Kb9 1. h4 h6 2. Bh1 Rb7 3. Kb9 1. h4 h6 2. Bh1 Rb7 3. Kb9 1. h4 h6 2. Bh1 Rb7 3. Kb9 1. h4 h6 2. Bh1 Rb7 3. Kb9</span></td>
                                <td><span class="choice">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                        <input type="hidden" value="accept" name="review">
                                        <button type="submit" class="button green"><span><i class="fa fa-check"></i></span></button>
                                    </form>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                        <input type="hidden" value="delete" name="review">
                                        <button type="submit" class="button red"><span><i class="fa fa-close"></i></span></button>
                                    </form>
                                </span></td>
                            </tr>-->
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