<?php
function isLogout() {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        header("Location: ./user/dashboard.php");
        exit();
    }
}
?>