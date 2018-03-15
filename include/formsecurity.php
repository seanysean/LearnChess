<?php
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