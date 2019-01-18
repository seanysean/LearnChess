<?php
session_start();
include "../../include/functions.php";
$accountid = $account['id'];
$myaccount = $l && ($accountid === $_SESSION['userid']);
$sql = "SELECT * FROM `users` WHERE id='$accountid'";
$result = mysqli_query($connection,$sql);
if ($result) {
    $res = $result->fetch_assoc();
    $thisUsersName = $res['name'];
    $coordinates = $res['coordinates'];
    $coords = $res['showcoords'];
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
        $icon = 'fa-user';
        $hint = '';
    }
    $online = $res['online'];
    $active = $res['active'] === '1';
    $created = date('M. d Y',strtotime($res['created']));
    $last_active = date('M. d Y',strtotime($res['last_active']));
    $views = $res['views'];
    $rating = round($res['rating']);
    $sql = "SELECT id FROM `puzzles_approved` WHERE author_id='$accountid' AND removed='0'";
    $puzzle_count = mysqli_num_rows(mysqli_query($connection,$sql));
    $sql = "SELECT `rating` FROM `puzzles_history` WHERE user='$accountid' AND `profile`='1'";
    $puzzle_rating_result = mysqli_query($connection,$sql);
    $sql = "SELECT `date` FROM `puzzles_history` WHERE user='$accountid' AND `profile`='1'";
    $puzzle_date_result = mysqli_query($connection,$sql);
    $r_count = mysqli_num_rows($puzzle_rating_result);
    if ($l && !$myaccount && $active) {
        $views++;
        mysqli_query($connection,"UPDATE `users` SET views='$views' WHERE id='$accountid'");
    }
    if ($aboutThisUser) {
        $aboutThisUser = preg_replace('/(.|\s)@(\w+)/','\1<a href="/member/\2" powertip=\'{"type":"user","value":"?\2"}\'>@\2</a>',$aboutThisUser);
        $aboutThisUser = preg_replace('/(.|\s)p#(\w+)/','\1<a href="/puzzles/view/\2">p#\2</a>',$aboutThisUser);
    }
}
// Yes, the code is messy
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $account['username'] ?> • LearnChess</title>
        <?php include_once "../../include/head.php" ?>
        <?php if ($active) { ?><meta name="description" content="View <?php echo $account['username'] ?>'s profile on LearnChess.'"><?php } ?>
        <link href="../css/profile.css" type="text/css" rel="stylesheet">
        <link href="../css/chessground.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../../include/attributes.php" ?>>
        <div class="top"><?php include_once "../../include/topbar.php" ?></div>
        <div class="page">
            <div class="main full">
                <div class="block transparent">
                    <h1 class="block-title">
                        <span<?php echo $hint ?>>
                            <span class="fa <?php echo $icon ?> state<?php echo $online === '1' ? ' online' : ' offline' ?>"></span>
                        </span>
                        <span class="username"><?php echo $account['username'] ?></span>
                        <?php if($myaccount or isAllowed('admin')) { ?>
                        <span class="alternate">
                            <?php if($myaccount) { ?>
                            <a href="/settings/profile" class="button blue"><span><i class="fa fa-pencil"></i> Edit profile</span></a>
                            <?php } else if (isAllowed('admin')) { ?>
                                <a href="/admin/search?username=<?php echo $account['username']; ?>" class="button blue"><span><i class="fa fa-shield"></i> View and edit info</span></a>
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
                        echo "<a class=\"other-profile-link\" rel=\"nofollow\" target=\"_blank\" href=\"https://lichess.org/@/$thisUsersLichessProfile\">View lichess profile <i class=\"fa fa-external-link\"></i></a>";
                    }
                    if($thisUsersChesscomProfile) {
                        echo "<a class=\"other-profile-link\" rel=\"nofollow\" target=\"_blank\" href=\"https://chess.com/member/$thisUsersChesscomProfile\">View chess.com profile <i class=\"fa fa-external-link\"></i></a>";
                    } ?>
                </div>
                <div class="block no-padding info-block">
                    <div class="info-bar<?php if ($aboutThisUser) { echo " info-color-change"; } ?>">
                        <div class="info">
                            <span class="icon-title fa fa-user"></span>
                            <span class="info-details">Joined</span>
                            <span class="info-value"><?php echo $created ?></span>
                        </div>
                        <div class="info">
                            <span class="icon-title fa fa-key"></span>
                            <span class="info-details">Last active</span>
                            <span class="info-value"><?php echo $last_active ?></span>
                        </div>
                        <?php if ($views) { // No one wants a stat of 0 views :) ?>
                        <div class="info">
                            <span class="icon-title fa fa-eye"></span>
                            <span class="info-details">Views</span>
                            <span class="info-value"><?php echo $views ?></span>
                        </div>
                        <?php } ?>
                        <div class="info">
                            <span class="icon-title fa fa-puzzle-piece"></span>
                            <span class="info-details">Puzzle Contributions</span>
                            <span class="info-value"><?php echo $puzzle_count ?></span>
                        </div>
                        <div class="info">
                            <span class="icon-title fa fa-line-chart"></span>
                            <span class="info-details">Rating</span>
                            <span class="info-value"><?php echo $rating ?></span>
                        </div>
                        <?php if($coordinates && $coords) { ?>
                        <div class="info">
                            <span class="icon-title fa fa-bullseye"></span>
                            <span class="info-details">Coordinates</span>
                            <span class="info-value"><?php echo $coordinates ?></span>
                        </div>
                        <?php } /*
                        if($thisUsersChesscomProfile) { ?>
                        <a href="https://chess.com/member/<?php echo $thisUsersChesscomProfile ?>" target="_blank" rel="nofollow" class="info" id="chessComInfo">
                            <div class="loader"></div>
                        </a>
                        <?php }
                        if($thisUsersLichessProfile) { ?>
                        <a href="https://lichess.org/@/<?php echo $thisUsersLichessProfile ?>" target="_blank" rel="nofollow" class="info" id="lichessInfo">
                            <div class="loader"></div>
                        </a>
                        <?php } */ ?>
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
                <?php } ?>
                <div class="block">
                    <h1 class="block-title">Puzzle rating history</h1>
                    <?php if ($r_count > 0) { ?>
                    <div id="puzzleRating">
                        <div id="loading" class="loader"></div>
                    </div>
                    <?php } else {
                        echo "<p class=\"nothing-to-see lower\">No puzzles attempted yet</p>";
                    } ?>
                </div>
                <?php } else { echo $active; } ?>
            </div>
        </div>
        <footer>
        <?php include "../../include/footer.php" ?>
        </footer>
        <script>
        const web = {
            chessCom: <?php echo strlen($thisUsersChesscomProfile) ? "'$thisUsersChesscomProfile'" : 'false'; ?>,
            lichess: <?php echo strlen($thisUsersLichessProfile) ? "'$thisUsersLichessProfile'" : 'false'; ?>
        }
        </script>
        <?php if ($r_count > 0 && $active) { ?>
        <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
        <script>
            const record = {
                <?php $n = 1; $c = 1; ?>
                x: [<?php while($row = mysqli_fetch_array($puzzle_date_result,MYSQLI_ASSOC)) { echo "'".$row['date']."'"; if ($c < $r_count) { echo ","; } $c++; } ?>],
                y: [<?php while($row = mysqli_fetch_array($puzzle_rating_result,MYSQLI_ASSOC)) { echo "'".$row['rating']."'"; if ($n < $r_count) { echo ","; } $n++; } ?>],
                type: 'scatter'
            }
            let lower;
            if (record.x.length <= 10) {
                lower = record.x[0];
            } else {
                lower = record.x[record.x.length - 10];
            }
            const layout = {
                xaxis: {
                    autorange: true,
                    range: [lower,record.x[record.x.length - 1]],
                    rangeslider: {
                        range: [lower,record.x[record.x.length - 1]]
                    },
                    type: 'date'
                }
            }
            Plotly.newPlot('puzzleRating',[record],layout);
            document.getElementById('loading').style.display = 'none';
        </script>
        <?php } ?>
        <script src="../js/profile.js"></script>
        <script src="../js/global.js"></script>
        <script src="../js/chessground.min.js"></script>
        <script src="../js/loadposition.js"></script>
    </body>
</html>
