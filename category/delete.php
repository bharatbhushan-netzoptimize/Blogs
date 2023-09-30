<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");


$category = new Category();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $result = $category->delete($id);

    if ($result === true) {
        $_SESSION['delete_success'] = true;
        header("Location: ../category/index.php");
        exit();
    } else {
        echo $result;
    }
} else {
    echo "Invalid blog ID.";
}

?>