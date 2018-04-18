<?php
session_start();
include "../include/functions.php";
$accountid = $account['id'];
$sql = "SELECT * FROM `users` WHERE id='$accountid'";
$result = mysqli_query($connection,$sql);
if ($result) {
    $res = $result->fetch_assoc();
    $thisUsersName = $res['name'];
    $coordinates = $res['coordinates'];
    $thisUsersLichessProfile = $res['lichess'];
    $thisUsersChesscomProfile = $res['chesscom'];
    $aboutThisUser = $res['about'];
    $p = str_split($res['permissions']);
    if ($p[0] === '1') {
        $icon = 'fa-shield';
        $hint = ' data-hint="Admin"';
    } else if ($p[1] === '1') {
        $icon = 'fa-puzzle-piece';
        $hint = ' data-hint="Puzzle reviewer"';
    } else {
        $icon = 'fa-circle';
        $hint = '';
    }
    $online = $res['online'];
    $active = $res['active'] === '1' ? true : false;
    $created = date('M. d Y',strtotime($res['created']));
    $last_active = date('M. d Y',strtotime($res['last_active']));
    $sql = "SELECT id FROM `puzzles_approved` WHERE author_id='$accountid' AND removed='0'";
    $puzzle_count = mysqli_num_rows(mysqli_query($connection,$sql));
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $account['username'] ?> • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <link href="../css/profile.css" type="text/css" rel="stylesheet">
        <link href="../css/chessground.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="top"><?php include_once "../include/topbar.php" ?></div>
        <div class="page">
            <div class="main full">
                <div class="block transparent">
                    <h1 class="block-title">
                        <span<?php echo $hint ?>>
                            <span class="fa <?php echo $icon ?> state<?php echo $online === '1' ? ' online' : ' offline' ?>"></span>
                        </span>
                        <?php echo $account['username'] ?>
                        <?php if($l and ($accountid === $_SESSION['userid'] or isAllowed('admin'))) { ?>
                        <span class="alternate">
                            <?php if($accountid === $_SESSION['userid']) { ?>
                            <a href="/settings/profile" class="button blue"><span><i class="fa fa-pencil"></i> Edit profile</span></a>
                            <?php } else if (isAllowed('admin')) { 
                                $usrnm = $account['username']; ?>
                                <a href="/admin/search?username=<?php echo $usrnm ?>" class="button blue"><span><i class="fa fa-shield"></i> View and edit info</span></a>
                            <?php } ?>
                        </span>
                        <?php } ?>
                    </h1>
                    <?php if($thisUsersName) { ?>
                    <p class="name"><?php echo $thisUsersName ?></p>
                    <?php } if (!$active) {
                        echo "<h2><i class=\"fa fa-ban\"></i> Account closed</h2>";
                    } else {
                    if($thisUsersLichessProfile) {
                        echo "<a class=\"other-profile-link\" target=\"_blank\" href=\"https://lichess.org/@/$thisUsersLichessProfile\">View lichess profile <i class=\"fa fa-external-link\"></i></a>";
                    }
                    if($thisUsersChesscomProfile) {
                        echo "<a class=\"other-profile-link\" target=\"_blank\" href=\"https://chess.com/member/$thisUsersChesscomProfile\">View chess.com profile <i class=\"fa fa-external-link\"></i></a>";
                    } ?>
                </div>
                <div class="block">
                    <div class="info-bar">
                        <div class="info">
                            <span class="info-title">Account created</span>
                            <?php echo $created ?>
                        </div>
                        <div class="info">
                            <span class="info-title">Last active</span>
                            <?php echo $last_active ?>
                        </div>
                        <div class="info">
                            <span class="info-title">Puzzles created</span>
                            <?php echo $puzzle_count ?>
                        </div>
                        <?php if($coordinates) { ?>
                        <div class="info">
                            <span class="info-title">Coordinates</span>
                            <?php echo $coordinates ?>
                        </div>
                        <?php }
                        if($thisUsersChesscomProfile) { ?>
                        <a href="https://chess.com/member/<?php echo $thisUsersChesscomProfile ?>" target="_blank" data-hint="Chess.com profile" class="info" id="chessComInfo">
                            <div class="loader"></div>
                        </a>
                        <?php }
                        if($thisUsersLichessProfile) { ?>
                        <a href="https://lichess.org/@/<?php echo $thisUsersLichessProfile ?>" target="_blank" data-hint="Lichess.org profile" class="info" id="lichessInfo">
                            <div class="loader"></div>
                        </a>
                        <?php } ?>
                    </div>
                    <?php if($aboutThisUser) { ?><div class="about" id="about"><?php echo $aboutThisUser ?></div><?php } ?>
                    <?php } ?>
                </div>
                <?php if($active) { 
                $sql = "SELECT id,fen FROM `puzzles_approved` WHERE author_id='$accountid' AND removed='0' ORDER BY id DESC LIMIT 8";
                $result = mysqli_query($connection,$sql);
                if (mysqli_num_rows($result)) { ?>
                <div class="block">
                    <h1 class="block-title">Contributed puzzles<?php if ($puzzle_count) { echo " ($puzzle_count)"; } ?></h1>
                    <div class="puzzle-list">
                        <?php while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) { ?>
                        <div class="board-container">
                            <a href="/puzzles/view/<?php echo $row['id'] ?>" class="board" data-fen="<?php echo $row['fen'] ?>"></a>
                            <div class="credits">Puzzle <?php echo $row['id'] ?></div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
        <footer>
        <?php include "../include/footer.php" ?>
        </footer>
        <script>
        const web = {
            chessCom: <?php echo strlen($thisUsersChesscomProfile) ? "'$thisUsersChesscomProfile'" : 'false'; ?>,
            lichess: <?php echo strlen($thisUsersLichessProfile) ? "'$thisUsersLichessProfile'" : 'false'; ?>
        }
        </script>
        <script src="../js/profile.js"></script>
        <script src="../js/global.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/loadposition.js"></script>
    </body>
</head>
