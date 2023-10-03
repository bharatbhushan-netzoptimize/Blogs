<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isUser.php");
isUser();

$blog = new Blog();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $result = $blog->delete($id);

    if ($result === true) {
        $_SESSION['delete_success'] = true;
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        echo $result;
    }
} else {
    echo "Invalid blog ID.";
}

?>