<?php 

if (isset($_SESSION['settings']['dark_theme']) && $_SESSION['settings']['dark_theme'] === true) {
    echo " class=\"dark\"";
}
