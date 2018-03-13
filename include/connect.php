<?php
require "connectiondetails.php";
$connection = mysqli_connect("localhost", "$databaseuser", "$databasepassword");
if (!$connection){
    die("Database Connection Failed" . mysqli_error($connection));
}
$select_db = mysqli_select_db($connection, 'id5026529_lc');
if (!$select_db){
    die("Database Selection Failed" . mysqli_error($connection));
}
