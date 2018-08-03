<?php
session_start();
include "../../include/functions.php";
if (!$l or !isAllowed('puzzle')) {
    header('Location: /');
} else if (isset($_POST['puzzle'])) { 
    $puzzle = secure($_POST['puzzle']);
    if(isset($_POST['undo'])) {
        $sql = "UPDATE `puzzles_approved` SET removed='0' WHERE id='$puzzle'";
    } else {
        $sql = "UPDATE `puzzles_approved` SET removed='1' WHERE id='$puzzle'";
    }
    $result = mysqli_query($connection,$sql);
    if ($result) {
        header("Location: view/$puzzle");
    }
}
