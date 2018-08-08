<?php session_start();
include "../include/functions.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>About • LearnChess</title>
        <?php include_once "../include/head.php" ?>
        <meta name="description" content="Learn about LearnChess, a free chess website with no premium accounts and no ads.">
        <link href="css/page.css" type="text/css" rel="stylesheet">
        <style>
        .github {
            color: #545454;
        }
        .github:hover {
            background: #545454 !important;
        }
        </style>
    </head>
    <body<?php include_once "../include/attributes.php" ?>>
        <div class="top">
            <?php include_once "../include/topbar.php" ?>
        </div>
        <div class="page">
            <div class="main">
                <div class="block">
                    <h1 class="block-title">About LearnChess</h1>
                    <p>&nbsp;&nbsp;LearnChess is a small website for getting better at chess.
                    You can <a href="puzzles/next">practice chess tactics</a>, <a href="computer">play the computer</a>, and try the <a href="coordinates">coordinates trainer</a>.
                    All of these features are 100% free and there are zero ads (like <a href="https://lichess.org" rel="nofollow" target="_blank">Lichess <i class="fa fa-external-link"></i></a>).
                    LearnChess is similar to <a href="https://chessvariants.training" rel="nofollow" target="_blank">Chessvariants.training <i class="fa fa-external-link"></i></a> in that anyone can contribute a puzzle.
                    </p>
                    <h2>Why use LearnChess?</h2>
                    <p>&nbsp;&nbsp;Use it if you want something that is <a href="https://github.com/seanysean/LearnChess" rel="nofollow" target="_blank">open source <i class="fa fa-external-link"></i></a>, 100% free (no premium accounts), and has no advertisements.
                    Since all puzzles are submitted and reviewed by real people, they should be more instructive than computer generated puzzles.
                    </p>
                </div>
            </div>
            <div class="right-area">
                <div class="block">
                    <h1 class="block-title">Links</h1>
                    <a href="https://github.com/seanysean/LearnChess" rel="nofollow" target="_blank" class="github"><i class="fa fa-github"></i> GitHub</a>
                </div>
            </div>
        </div>
        <footer><?php include_once "../include/footer.php" ?></footer>
        <script src="js/global.js"></script>
    </body>
</html>
