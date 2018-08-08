<?php
function sidebar($n) {
    switch ($n) {
        case 1:
            echo '<div class="block sidebar">
                <a href="review" class="current"><i class="fa fa-dashboard"></i> Puzzles to review</a>
                <a href="removed"><i class="fa fa-close"></i> Removed puzzles</a></div>';
            break;
        case 2:
            echo '<div class="block sidebar">
            <a href="review"><i class="fa fa-dashboard"></i> Puzzles to review</a>
            <a href="removed" class="current"><i class="fa fa-close"></i> Removed puzzles</a></div>';
            break;
    }
}
