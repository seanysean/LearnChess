<div class="top-above">
    <a class="learnchess-link" href="/">LearnChess<span class="extension"></a>
    <?php if(isset($_SESSION['username'])) { ?>
        <span class="right"><a class="profile-link" href="#"><?php echo $_SESSION['username']; ?></a></span>
    <?php } else { ?>
        <span class="right"><a class="profile-link" href="/login">Login</a> / <a class="profile-link" href="/register">Register</a></span>
    <?php } ?>
</div>
<nav class="top-navigation">
    <div class="main-links">
        <a href="/home">Home</a>
        <div class="main-dropdown">
            <a href="#">More</a>
            <div class="dropdown">
                <a href="/about">About</a>
            </div>
        </div>
    </div>
    <?php if(isset($_SESSION['username'])) { ?><div class="icon-links">
        <div class="icon-dropdown">
            <a class="fa fa-cog"></a>
            <div class="dropdown">
                <a href="/logout"><span class="fa fa-sign-out"></span> Logout</a>
            </div>
        </div>
    </div>
    <?php } ?>
</nav>