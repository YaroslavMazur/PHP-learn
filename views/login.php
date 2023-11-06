<?php

$ERRORS = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $login = $_POST["login"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (!preg_match("/^[a-zA-Zа-яА-Я0-9_-]{4,}$/u", $login)) {
        $ERRORS["login"] = "Логін повинен складатись з 4 літер/цифр/-/_";
    }

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{7,}$/", $password)) {
        $ERRORS["password"] = "Не менше 7 літер, обов’язково має містити великі та малі літери, а також цифри";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ERRORS["email"] = "Некоректний формат email-адреси";
    }


    $mysqli = new mysqli("localhost", "pma", "admin","website1");

    if ($mysqli->connect_errno != 0) {
        die($mysqli->connect_error);
    }

    $query = $mysqli->prepare("SELECT * FROM users WHERE email = ? LIMIT 1;");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $userData = $result->fetch_assoc();

    if($query->errno != 0) {
        die($query->error);
    }

    if($result->num_rows == 0 || !password_verify($password, $userData["password"])) {
            
        $ERRORS["email_or_password_wrong"] = "Неправильний емайл, або пароль";
    }
    

    

    if (empty($ERRORS)) {
        
        session_start();
        $_SESSION['id'] = $userData['id'];
        $_SESSION['login'] = $userData['login'];
        $_SESSION['is_admin'] = $userData['is_admin'];

        header("Location: index.php");
        die();
    }
}
?>


<div class = "form-container">
    <form action = "" method = "POST" class = "form">
        <h1 style = "margin-bottom:20px;">Log in</h1>
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

        <label for = "email">Email</label>
        <input type = 'email' name = "email" required>

        <p class = "ERROR">

            <?php 
                if(isset($ERRORS["email_exist"])) echo $ERRORS["email_exist"];
                else if(isset($ERRORS['email'])) echo $ERRORS['email'];
                else echo "";

            ?>
        </p>

        <button type = "submit" class = "Submit">Log in</button>


        <p class = "ERROR">
            <?php 
                if(isset($ERRORS['email_or_password_wrong'])) echo $ERRORS['email_or_password_wrong'];
                else echo "";

            ?>
        </p>
        
        <a href="index.php?action=registration" style="color:white;">Don't have an account?</a>

    </form>
</div>