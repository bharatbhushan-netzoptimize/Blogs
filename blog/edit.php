<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");

$blogEditor = new Blog();

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
        $_SESSION['update_success'] = true;
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
        <input type="text" name="heading" value="<?=$blog['heading']?>">

        <label for="subheading">Sub Heading:</label>
        <input type="text" name="subheading" value="<?=$blog['sub_heading']?>">

        <label for="content">Content:</label>
        <textarea name="content"><?=$blog['content']?></textarea>

        <input type="submit" name="update" value="Update">
    </form>
</div>

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>