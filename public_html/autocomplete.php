<?php
header('Content-Type', 'application/json');
require "../include/functions.php";
if (isset($_GET['username'])) {
    if(isset($_GET['exists']) && $_GET['exists'] === "1") {
        $input = secure($_GET['username']);
        $sql = "SELECT username FROM `users` WHERE username='$input' LIMIT 1";
        $result = mysqli_query($connection,$sql);
        $count = mysqli_num_rows($result);
        if ($count === 1) {
            $return = Array('exists' => true);
        } else {
            $return = Array('exists' => false);
        }
        echo json_encode($return);
    } else if (isset($_GET['limit'])) {
        $input = secure($_GET['username']);
        $limit = secure($_GET['limit']);
        $limit = ($limit > 10) ? 10 : $limit; // Prevent people from being able to view all users and crash the db.
        $sql = "SELECT * FROM `users` WHERE username LIKE '$input%' LIMIT $limit";
        $json = '[';
        $result = mysqli_query($connection,$sql);
        $num_rows = mysqli_num_rows($result);
        $num = 0;
        if ($num_rows > 0) {
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                $num++;
                $permissions = $row['permissions'];
                switch ($permissions) {
                    case '00':
                        $return['icon'] = 'user';
                        break;
                    case '01':
                        $return['icon'] = 'puzzle-piece';
                        break;
                    default:
                        $return['icon'] = 'shield';
                        break;
                }
                $return['username'] = $row['username'];
                $return['state'] = $row['online'] === '1' ? 'online' : 'offline';
                $return['id'] = $row['id'];
                $json .= json_encode($return);
                if ($num < $num_rows) {
                    $json .= ',';
                }
            }
            echo $json.']';
        } else {
            echo "false";
        }
    }
}
