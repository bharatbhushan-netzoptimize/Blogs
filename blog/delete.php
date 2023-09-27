<?php
session_start();
include '../auth/isLogin.php';
isLogin();
include '../includes/DatabaseConnection.php';
include '../blog/Blog.php';

$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$blog = new Blog($database,$_SESSION['user_id']);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $result = $blog->delete($id);

    if ($result === true) {
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        echo $result;
    }
} else {
    echo "Invalid blog ID.";
}

?>