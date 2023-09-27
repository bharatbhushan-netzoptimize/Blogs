<?php
session_start();
include '../auth/isLogin.php';
isLogin();
include '../includes/header.php';
include '../includes/DatabaseConnection.php';
include '../blog/Blog.php';

$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$blog = new Blog($database,$_SESSION['user_id']);

if (isset($_POST["submit"])) {
    $heading = $_POST['heading'];
    $subHeading = $_POST['subheading'];
    $content = $_POST['content'];

    $result = $blog->create($heading, $subHeading, $content);

    if ($result === true) {
        header('Location: ../user/dashboard.php');
        exit;
    } else {
        echo $result;
    }
}
?>
<div class="form-container">
    <h2>New Blog</h2>
    <form method="post">
        <label for="heading">Heading</label>
        <input type="text" name="heading" placeholder="Enter heading" required>
        <label for="subheading">Sub Heading</label>
        <input type="text" name="subheading" placeholder="Enter subheading" required>
        <label for="content">Content</label>
        <textarea name="content" id="" cols="30" rows="10" required></textarea>
        <button type="submit" name="submit">Create</button>
    </form>
</div>

