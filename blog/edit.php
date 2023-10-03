<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isUser.php");
isUser();
$blogEditor = new Blog();
$user = new User;   
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $blog = $blogEditor->getBlog($id);
    if($blog['user_id']!=$_SESSION['user_id']){
        echo "Invalid blog ID.";
        exit();
    }

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
    
    if (empty($newHeading)) {
        $errors['heading'] = "Heading is required.";
    }
    if (strlen($newHeading) > 255) {
        $errors['heading'] = "Heading should not exceed 255 characters.";
    }
    if (empty($newSubHeading)) {
        $errors['subheading'] = "Sub Heading is required.";
    }
    if (strlen($newSubHeading) > 300) {
        $errors['subheading'] = "Subheading should not exceed 300 characters.";
    }
    if (empty($newContent)) {
        $errors['content'] = "Content is required.";
    }
    if (empty($errors)) {
        $result = $blogEditor->updateBlog($id, $newHeading, $newSubHeading, $newContent);
        if ($result === true) {
            $_SESSION['update_success'] = true;
            ?>
            <script>
                window.location.replace('../user/dashboard.php');
            </script>
            <?php
            exit;
        } else {
            echo $result;
        }
    }
}
?>
<div class="form-container">
    <h2>Edit Blog Post</h2>
    <form method="post">
        <label for="heading">Heading:</label>
        <input type="text" name="heading" value="<?=$blog['heading']?>">
        <?php if (!empty($errors['heading'])): ?>
            <p class="error-text">
                <?= $errors['heading']; ?>
            </p>
        <?php endif; ?>
        <label for="subheading">Sub Heading:</label>
        <input type="text" name="subheading" value="<?=$blog['sub_heading']?>">
        <?php if (!empty($errors['subheading'])): ?>
            <p class="error-text">
                <?= $errors['subheading']; ?>
            </p>
        <?php endif; ?>

        <label for="content">Content:</label>
        <textarea name="content"><?=$blog['content']?></textarea>
        <?php if (!empty($errors['content'])): ?>
            <p class="error-text">
                <?= $errors['content']; ?>
            </p>
        <?php endif; ?>

        <button type="submit" name="update">Update</button>
    </form>
    <a href="../user/dashboard.php"><button>Cancel</button></a>

</div>0

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>