<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ERRORS = array();

    $login = $_POST["login"];
    $password = $_POST["password"];
    $repeatPassword = $_POST["repeatPassword"];
    $email = $_POST["email"];
    $region = $_POST["region"];

    if (!preg_match("/^[a-zA-Zа-яА-Я0-9_-]{4,}$/u", $login)) {
        $ERRORS["login"] = "Логін повинен складатись з 4 літер/цифр/-/_";
    }

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{7,}$/", $password)) {
        $ERRORS["password"] = "Не менше 7 літер, обов’язково має містити великі та малі літери, а також цифри";
    }

    if ($password !== $repeatPassword) {
        $ERRORS["repeatPassword"] = "Паролі не співпадають.";
    }

    if($region == ""){
        $ERRORS["region"] = "Виберіть область";
    }

    $mysqli = new mysqli("localhost", "pma", "admin","website1");

    if ($mysqli->connect_errno != 0) {
        die($mysqli->connect_error);
    }

    $query = $mysqli->prepare("SELECT * FROM users WHERE email = ?;");
    $query->bind_param("s", $email);
    $query->execute();
    
    $result = $query->get_result();

    if($query->errno != 0) {
        die($query->error);
    }

    if($result->num_rows != 0) {
        $ERRORS["email"] = "Користувач з таким email вже зареєстрований";
    }

    if (empty($ERRORS)) {
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);       
        
        $query = $mysqli->prepare("INSERT INTO users (login, email, password, region) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss",$login, $email, $password_hashed, $region);
        $query->execute();

        if($query->errno != 0) {
            die($query->error);
        }
        
        
        header("Location: index.php?action=main");
        die();
    }
}
?>


<div class = "form-container">
    <form action = "" method = "POST" class = "form">
        <h1 style = "margin-bottom:20px;">Registration</h1>
        <label for = "login">Login</label>
        <input type = 'text' name = "login" required>

        <p class = "ERROR">
        <?php 
            if(isset($ERRORS["login"])) echo $ERRORS["login"];
            else echo "";

        ?>
        </p>

        <label for = "password">Password</label>
        <input type = 'password' name = "password" required>
        <p class = "ERROR">
        <?php 
            if(isset($ERRORS["password"])) echo $ERRORS["password"];
            else echo "";

        ?>
        </p>
        
        <label for = "repeatPassword">Repeat password</label>
        <input type = 'password' name = "repeatPassword" required>
        <p class = "ERROR">
            <?php 
                if(isset($ERRORS["repeatPassword"])) echo $ERRORS["repeatPassword"];
                else echo "";
            ?>  
        </p>

        <label for = "email">Email</label>
        <input type = 'email' name = "email" required>
        <p class = "ERROR">

            <?php 
                if(isset($ERRORS["email"])) echo $ERRORS["email"];
                else echo "";

            ?>
        </p>

        <select id="regions" name="region" required>
            <option value="" disabled selected>Select your region</option>
            <?php
            $regionsData = file_get_contents("./views/regData.txt");

            $regions = explode(",", $regionsData);
            sort($regions);

            foreach ($regions as $region) {
                list($name, $code) = explode(":", $region);
                echo "<option value = \"$code\">$name - $code</option>";
            }

            ?>
        </select>
        <p class = "ERROR">

            <?php 
                if(isset($ERRORS["region"])) echo $ERRORS["region"];
                else echo "";

            ?>
        </p>


        <button type = "submit" class = "Submit">Sing up</button>

    </form>
</div>
