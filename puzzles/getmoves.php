<?php
require "../include/functions.php";
if(isset($_GET['move']) && isset($_GET['movenum']) && isset($_GET['puzzle'])) {
    $move = secure($_GET['move']);
    $movenum = secure($_GET['movenum']);
    $puzzle = secure($_GET['puzzle']);
    $sql = "SELECT fen,pgn FROM `puzzles_approved` WHERE id='$puzzle'";
    $result = mysqli_query($connection,$sql);
    $res = $result->fetch_assoc();
    $pgn = preg_split('/[1-9][.][.][.] | [1-9][.] |\s/',$res['pgn']);
    $fen = explode(' ',$res['fen']);
    $color = $fen[1];
    $correct = $pgn[($movenum * 2) - 1];
    /*echo $res['pgn']."<br>";
    echo var_dump($pgn)."<br>";
    echo implode('',$pgn)."<br>";
    echo $correct === $move;*/
    $return = Array();
    if (sizeof($pgn) > $movenum * 2) {
        $return['next'] = $pgn[($movenum * 2)];
    } else {
        $return['ended'] = true;
    }
    if ($correct === $move) {
        $return['correct'] = true;
    } else {
        $return['correct'] = false;
        $return['best'] = $correct;
    }
    echo json_encode($return);
}
