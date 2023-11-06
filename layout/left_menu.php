


<nav class = "menu-container">
    
    <ul class = "menu">
        <li class = "menu-item"><a href="index.php?action=main">Main</a></li>
        <li class = "menu-item"><a href="index.php?action=about">About</a></li>
        <?php
            session_start();

            if(!empty($_SESSION)){
                echo '<li class = "menu-item"><a href="index.php?action=logout">Log out</a></li>';
            }
            else{
                echo '<li class = "menu-item"><a href="index.php?action=login">Log in</a></li>';
            }
            $a = array("b"=>"4","a"=>"8","c"=>"2");
            rsort($a);
            print_r($a);
        ?>

    </ul>
</nav>