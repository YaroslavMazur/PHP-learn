<?php
    require_once("layout/header.php");
    require_once("layout/left_menu.php");

    $action = isset($_GET['action']) ? $_GET['action'] : 'main';

    $viewFiles = scandir("views");

    $viewFiles = array_diff($viewFiles, array('.', '..'));

    if (in_array($action . '.php', $viewFiles)) {
        require_once('views/' . $action . '.php');
    } 
    else{
        echo "<div style = 'height: 100vh; padding: 100px;'><h1>page not found 404</h1></div>";
    }
    require_once("layout/footer.php");
?>
