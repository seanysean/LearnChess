<?php session_start();
include "../include/functions.php";
if (!$l) {
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Get Better At Chess With LearnChess</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="A website for improving in chess. You can play vs. the computer, solve chess tactics, and more to come! It's 100% free, no ads, and open source. No registration required.">
        <link href="css/landing.css" type="text/css" rel="stylesheet">
        <link href="css/chessground.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,400" rel="stylesheet">
    </head>
    <body>
        <div class="topbar" id="topbar">
            <a class="site-name" href="/">LearnChess<span class="extension">.tk</span></a>
            <div class="right">
                <a href="login"><span>Login</span></a>
                <a href="register"><span>Register</span></a>
            </div>
        </div>
        <div class="main">
            <div class="content">
                <div class="left">
                    <img class="main-image" src="https://lichess1.org/assets/images/board/svg/brown.svg" alt="Get better at chess online for free at LearnChess" />
                </div>
                <div class="right">
                    <h1>Improve your chess skills for free</h1>
                    <p>Practice with puzzles</p>
                    <p>Play with the computer</p>
                    <p>Learn chess coordinates</p>
                </div>
            </div>
        </div>
        <div class="section puzzles">
            <div class="content">
                <div class="left">
                    <h1>Puzzles</h1>
                    <p>Solving chess tactics is a known way to get better at chess. Here at LearnChess you can do it for free. All puzzles are hand-picked. <a href="/puzzles/next">Try one</a></p>
                </div>
                <div class="right">
                    <div class="fa fa-puzzle-piece icon"></div>
                </div>
            </div>
        </div>
        <div class="section coordinates">
            <div class="content">
                <div class="right">
                    <h1>Coordinates</h1>
                    <p>Train your coordinates finding skills so that you will be more efficient reading chess books. <a href="/coordinates">Try now</a></p>
                </div>
                <div class="left">
                    <div class="fa fa-bullseye icon"></div>
                </div>
            </div>
        </div>
        <div class="section pricing">
            <div class="content">
                <div class="right">
                    <div class="fa fa-check-square icon"></div>
                </div>
                <div class="left">
                    <h1>Pricing</h1>
                    <p>Everything is absolutely free. There are no ads and no premium accounts.</p>
                    <div class="username-container">
                        <input id="username" type="text" required>
                        <label for="username">Enter username</label>
                        <a id="start" href="/register">Get started</a>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <span class="site-name">LearnChess <span class="year">2018</span></span>
            <div class="right">
                <a href="https://github.com/seanysean/LearnChess" target="_blank">Open source <i class="fa fa-external-link"></i></a> •
                <a href="/puzzles" data-hint="Puzzles"><i class="fa fa-puzzle-piece"></i></a>
                <a href="/coordinates" data-hint="Coordinates trainer"><i class="fa fa-bullseye"></i></a>
                <a href="/computer" data-hint="Play computer"><i class="fa fa-tv"></i></a>
            </div>
        </footer>
        <script src="js/landing.js"></script>
    </body>
</html>
<?php } else {
    header('Location: home');
}
