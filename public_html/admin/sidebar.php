<?php
function sidebar($page) {
    if ($page === 'new') {
        echo "<div class=\"block transparent\"><a href=\"search\"><i class=\"fa fa-search\"></i> Search</a><a class=\"current\" href=\"new\"><i class=\"fa fa-user-plus\"></i> New users</a></div>";
    } else if ($page === 'search') {
        echo "<div class=\"block transparent\"><a class=\"current\" href=\"search\"><i class=\"fa fa-search\"></i> Search</a><a href=\"new\"><i class=\"fa fa-user-plus\"></i> New users</a></div>";
    }
}
