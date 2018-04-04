<?php
function sidebar($page) {
    if ($page === 'new') {
        echo "<div class=\"block transparent\"><a href=\"search\">Search</a><a class=\"current\" href=\"new\">New users</a></div>";
    } else if ($page === 'search') {
        echo "<div class=\"block transparent\"><a class=\"current\" href=\"search\">Search</a><a href=\"new\">New users</a></div>";
    }
}
