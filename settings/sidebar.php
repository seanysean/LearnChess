<?php 
function sidebar($n) {
    if ($n === 1) {
        echo "<div class=\"block links-section\">
        <a href=\"profile\" class=\"current\"><i class=\"fa fa-edit\"></i> Profile</a>
        <a href=\"password\"><i class=\"fa fa-key\"></i> Change password</a>
        <a href=\"close\"><i class=\"fa fa-close\"></i> Close Account</a>
        </div>";
    } else if ($n === 2) {
        echo "<div class=\"block links-section\">
        <a href=\"profile\"><i class=\"fa fa-edit\"></i> Profile</a>
        <a href=\"password\" class=\"current\"><i class=\"fa fa-key\"></i> Change password</a>
        <a href=\"close\"><i class=\"fa fa-close\"></i> Close Account</a>
        </div>";
    } else if ($n === 3) {
        echo "<div class=\"block links-section\">
        <a href=\"profile\"><i class=\"fa fa-edit\"></i> Profile</a>
        <a href=\"password\"><i class=\"fa fa-key\"></i> Change password</a>
        <a href=\"close\" class=\"current\"><i class=\"fa fa-close\"></i> Close Account</a>
        </div>";
    }
}
