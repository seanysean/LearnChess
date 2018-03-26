<?php
include "connect.php";
#l is true if user is logged in.
$l = isset($_SESSION['username']);

function secure($input,$nlines=false) {
    #nlines is for <textarea>s.
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = str_replace("'",'&#39;',$input);
    $input = str_replace('"','&quote;',$input);
    if ($nlines) {
        $input = preg_replace('/\n/','<br />',$input);
    }
    $input = trim($input);
    return $input;
}
function isAllowed($type) {
    # Valid $types: 'puzzle'.
    $pEdited = str_split($_SESSION['permissions']);
    if ($type === "puzzle") {
        return ($pEdited[1] === '1') ? true : false;
    }
}
