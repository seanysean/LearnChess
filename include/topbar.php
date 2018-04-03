<div class="top-above">
    <a class="learnchess-link" href="/">LearnChess<span class="extension">.tk</span></a>
    <?php if($l) { ?>
        <span class="right"><a class="profile-link" href="/member/<?php echo strtolower($_SESSION['username']); ?>"><?php echo $_SESSION['username']; ?></a></span>
    <?php } else { ?>
        <span class="right"><a class="profile-link" href="/login">Login</a> / <a class="profile-link" href="/register">Register</a></span>
    <?php } ?>
</div>
<nav class="top-navigation">
    <div class="main-links">
        <?php if($l) { echo "<a href=\"/home\">Home</a>"; } ?>
        <a href="/puzzles/all">Puzzles</a>
        <!--<div class="main-dropdown">
            <a href="#">More</a>
            <div class="dropdown">
                <a href="/about">About</a>
            </div>
        </div>-->
    </div>
    <?php if($l) { ?><div class="icon-links">
        <?php if (isAllowed('puzzle')) { ?>
        <a class="fa fa-puzzle-piece hint-text-center" href="/puzzles/review" data-hint="Review puzzles"></a>
        <?php } ?>
        <?php if (isAllowed('admin')) { ?>
        <a class="fa fa-shield hint-text-center icon" href="/admin/search" data-hint="Admin"></a>
        <?php } ?>
        <div class="icon-dropdown">
            <i class="fa fa-bell hint-text-center icon" data-hint="Notifications" id="notification-icon"><span id="dCount"></span></i>
            <div class="n-container" id="notification-container">
            </div>
        </div>
        <div class="icon-dropdown">
            <a class="fa fa-cog icon" href="/settings/account"></a>
            <div class="dropdown">
                <a href="/logout"><span class="fa fa-sign-out"></span> Logout</a>
            </div>
        </div>
    </div>
    <?php } ?>
</nav>
