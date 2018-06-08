<?php
session_start();
require "../include/functions.php";
function calcRatings($u,$p,$w) {
    $ratings = Array();
    if ($u > $p) {
        $diff = $u - $p;
    } else {
        $diff = $p - $u;
    }
	if ($diff > 350) {
		$diff = 350;	
	}	
	$ratings['diff'] = $diff;
    if ($w) {
		if ($u > $p) {
        	$ratings['user'] = round($u - 0.04 * $diff + 16,2);
        	$ratings['puzzle'] = round($p + 0.04 * $diff - 16,2);
		} else {
			$ratings['user'] = round($u + 0.04 * $diff + 16,2);
        	$ratings['puzzle'] = round($p - 0.04 * $diff - 16,2);
		}
    } else {
		if ($u > $p) {
        	$ratings['user'] = round($u - 0.04 * $diff - 16,2);
        	$ratings['puzzle'] = round($p + 0.04 * $diff + 16,2);
		} else {
			$ratings['user'] = round($u + 0.04 * $diff - 16,2);
        	$ratings['puzzle'] = round($p - 0.04 * $diff + 16,2);
		}
    }
    return $ratings;
}
if(isset($_GET['move']) && isset($_GET['movenum']) && isset($_GET['puzzle'])) {
    if ($l) {
        $userid = $_SESSION['userid'];
    }
    $move = secure($_GET['move']);
    $movenum = secure($_GET['movenum']);
    $puzzle = secure($_GET['puzzle']);
    $sql = "SELECT fen,pgn FROM `puzzles_approved` WHERE id='$puzzle'";
    $result = mysqli_query($connection,$sql);
    $res = $result->fetch_assoc();
    $pgn = preg_split('/[1-9][.][.][.] |[1-9][.]|\s/',$res['pgn'],null,PREG_SPLIT_NO_EMPTY);
    $fen = explode(' ',$res['fen']);
    $color = $fen[1];
    $correct = $pgn[($movenum * 2) - 2];
    /*echo $res['pgn']."<br>";
    echo var_dump($pgn)."<br>";
    echo implode('',$pgn)."<br>";
    echo $color."<br>";*/
    $return = Array();
    $realNext = false;
    if (isset($_SESSION['nextpuzzle']) && $_SESSION['nextpuzzle'] === $puzzle) {
        $realNext = true;
    }
    if ($correct === $move) {
        $return['correct'] = true;
        if (sizeof($pgn) > $movenum * 2) {
            $return['next'] = $pgn[($movenum * 2) - 1];
        } else {
            $return['ended'] = true;
            $pRating = mysqli_query($connection,"SELECT rating FROM `puzzles_approved` WHERE id='$puzzle'")->fetch_assoc()['rating'];
            $return['ratings'] = Array('puzzle'=>$pRating);
            if ($l) {
                $return['ratings'] = Array('user' => $_SESSION['rating'],'puzzle'=>$pRating);
                if ($realNext) {
                    $ratings = calcRatings($_SESSION['rating'],$pRating,true);
                    $return['ratings'] = $ratings;
                    $_SESSION['rating'] = $ratings['user'];
                    $ur = $ratings['user'];
                    $pr = $ratings['puzzle'];
                    mysqli_query($connection,"UPDATE `users` SET rating='$ur',updated_history='1' WHERE id='$userid'");
                    mysqli_query($connection,"UPDATE `puzzles_approved` SET rating='$pr' WHERE id='$puzzle'");
                    mysqli_query($connection,"INSERT INTO `puzzles_history` (`user`,`puzzle`,`rating`,`date`) VALUES ('$userid','$puzzle','$ur',CURRENT_TIMESTAMP)");
                }
            } else {
                $_SESSION['completed'] = $puzzle;
            }
        }
    } else {
        $return['correct'] = false;
        $return['best'] = $correct;
        $pRating = mysqli_query($connection,"SELECT rating FROM `puzzles_approved` WHERE id='$puzzle'")->fetch_assoc()['rating'];
        $return['ratings'] = Array('puzzle'=>$pRating);
        if ($l) {
            $return['ratings'] = Array('user' => $_SESSION['rating'],'puzzle'=>$pRating);
            if ($realNext) {
                $ratings = calcRatings($_SESSION['rating'],$pRating,false);
                $return['ratings'] = $ratings;
                $_SESSION['rating'] = $ratings['user'];
                $ur = $ratings['user'];
                $pr = $ratings['puzzle'];
                mysqli_query($connection,"UPDATE `users` SET rating='$ur',updated_history='1' WHERE id='$userid'");
                mysqli_query($connection,"UPDATE `puzzles_approved` SET rating='$pr' WHERE id='$puzzle'");
                mysqli_query($connection,"INSERT INTO `puzzles_history` (`user`,`puzzle`,`rating`,`date`) VALUES ('$userid','$puzzle','$ur',CURRENT_TIMESTAMP)");
            }
        } else {
            $_SESSION['completed'] = $puzzle;
        }
    }
    echo json_encode($return);
}
