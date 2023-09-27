<?php
session_start();
include '../auth/isLogin.php';
isLogin();
include '../includes/header.php';
include '../includes/DatabaseConnection.php';
include '../blog/Blog.php';

$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$blogEditor = new Blog($database,$_SESSION['user_id']);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $blog = $blogEditor->getBlog($id);

    if ($blog === null) {
        echo "Blog post not found.";
        exit();
    }
} else {
    echo "Invalid blog ID.";
    exit();
}

if (isset($_POST['update'])) {
    $newHeading = $_POST['heading'];
    $newSubHeading = $_POST['subheading'];
    $newContent = $_POST['content'];

    $result = $blogEditor->updateBlog($id, $newHeading, $newSubHeading, $newContent);

    if ($result === true) {
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        echo $result;
    }
}
?>
<div class="form-container">
    <h2>Edit Blog Post</h2>
    <form method="post">
        <label for="heading">Heading:</label>
        <input type="text" name="heading" value="<?php echo $blog['heading']; ?>">

        <label for="subheading">Sub Heading:</label>
        <input type="text" name="subheading" value="<?php echo $blog['sub_heading']; ?>">

        <label for="content">Content:</label>
        <textarea name="content"><?php echo $blog['content']; ?></textarea>

        <input type="submit" name="update" value="Update">
    </form>
</div>

<?php
include '../includes/footer.php';
?>