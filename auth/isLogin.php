<?php
function isLogin()
{
    if (!isset($_SESSION['user_id']) && empty($_SESSION['user_id'])) {
        header("Location: ../blogs-oops/login.php");
        exit();
    }
}
?>