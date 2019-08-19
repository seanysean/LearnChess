<?php
session_start();
include "../../include/functions.php";
include "sidebar.php";
if ($l) {
    $u = $_SESSION['userid'];
    $settings = $_SESSION['settings'];
    $darkThemeOn = isset($settings['dark_theme']) ? $settings['dark_theme'] : false;
    if (isset($_POST['post1'])) {
        if (isset($_POST['theme'])) {
            $settings['dark_theme'] = true;
            $settings_json = json_encode($settings);
            $sql = "UPDATE `users` SET settings='$settings_json' WHERE id='$u'";
            $_SESSION['settings'] = $settings;
            $darkThemeOn = true;
        } else {
            $settings['dark_theme'] = false;
            $settings_json = json_encode($settings);
            $sql = "UPDATE `users` SET settings='$settings_json' WHERE id='$u'";
            $_SESSION['settings'] = $settings;
            $darkThemeOn = false;
        }
        $result = mysqli_query($connection,$sql);
        if ($result) {
            $msg = '<p>Settings saved successfully</p>';
        } else {
            $msg = '<p>Settings not saved. Please report.</p>';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Settings • LearnChess</title>
        <?php include_once "../../include/head.php" ?>
        <link href="../css/material.css" type="text/css" rel="stylesheet">
        <link href="../css/settings.css" type="text/css" rel="stylesheet">
    </head>
    <body<?php include_once "../../include/attributes.php" ?>>
        <div class="top">
        <?php include_once "../../include/topbar.php" ?>
        </div>
        <div class="page has-left">
            <div class="left-area">
            <?php echo sidebar(1) ?>
            </div>
            <div class="main">
                <div class="block">
                    <h1 class="block-title"><i class="fa fa-cog"></i> Settings</h1>
                    <form action="/settings/" method="post">
                    <?php if(isset($msg)) { echo $msg; } ?>
                        <div class="checkbox-container">
                            <input name="post1" type="hidden" />
                            <input type="checkbox" name="theme" id="theme" <?php if ($darkThemeOn) { echo "checked "; } ?>/>
                            <label for="theme" class="custom-checkbox"></label>
                            <label for="theme" class="checkbox-message">Dark theme</label>
                        </div>
                        <button type="submit" class="button blue"><span><i class="fa fa-check"></i> Update settings</span></button>
                    </form>
                </div>
            </div>
        </div>
        <footer>
        <?php include_once "../../include/footer.php" ?>
        </footer>
        <script src="../js/global.js"></script>
    </body>
</html>
<?php 
} else {
    header('Location: /');
}
?>
