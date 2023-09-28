<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");

$blog = new Blog();

if (isset($_POST["submit"])) {
    $heading = $_POST['heading'];
    $subHeading = $_POST['subheading'];
    $content = $_POST['content'];

    if (empty($heading)) {
        $errors['heading'] = "Heading is required.";
    }
    if (strlen($heading) > 255) {
        $errors['heading'] = "Heading should not exceed 255 characters.";
    }
    if (empty($subHeading)) {
        $errors['subheading'] = "Sub Heading is required.";
    }
    if (strlen($subHeading) > 300) {
        $errors['subheading'] = "Subheading should not exceed 300 characters.";
    }
    if (empty($content)) {
        $errors['content'] = "Content is required.";
    }
    if (empty($errors)) {
        $result = $blog->create($heading, $subHeading, $content);

        if ($result === true) {
            header('Location: ../user/dashboard.php');
            exit;
        } else {
            echo $result;
        }
    }
}
?>

<div class="form-container">
    <h2>New Blog</h2>
    <form method="post">
        <label for="heading">Heading</label>
        <input type="text" name="heading" placeholder="Enter heading" value="<?= !empty($heading) ? $heading : '' ?>"
            required>
        <?php if (!empty($errors['heading'])): ?>
            <p class="error-text">
                <?= $errors['heading']; ?>
            </p>
        <?php endif; ?>

        <label for="subheading">Sub Heading</label>
        <input type="text" name="subheading" placeholder="Enter subheading"
            value="<?= !empty($subHeading) ? $subHeading : '' ?>" required>
        <?php if (!empty($errors['subheading'])): ?>
            <p class="error-text">
                <?= $errors['subheading']; ?>
            </p>
        <?php endif; ?>

        <label for="content">Content</label>
        <textarea name="content" id="" cols="30" rows="10" required><?= !empty($content) ? $content : '' ?></textarea>
        <?php if (!empty($errors['content'])): ?>
            <p class="error-text">
                <?= $errors['content']; ?>
            </p>
        <?php endif; ?>

        <button type="submit" name="submit">Create</button>
    </form>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>