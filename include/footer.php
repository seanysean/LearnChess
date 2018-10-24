<?php include "config.php"; ?>
<a class="site-name" href="<?php if ($l) { echo '/home'; } else { echo '/'; } ?>">LearnChess.tk</a>
<div class="right">
    <a href="/contact">Contact</a> •
    <a href="/about">About</a> •
    <a href="/thanks">Thanks</a>
</div>
<?php if ($devMode) {?>
<div class="dev">
    <i class="fa fa-eye"></i>
    <input type="checkbox" id="dev-theme-switch">
    <label class="dev-switch" for="dev-theme-switch">
        <span class="dev-inner"></span>
    </label>
</div>
<script src="/js/dev.js"></script>
<?php } ?>
