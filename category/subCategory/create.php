<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/subCategory/SubCategory.php");

$subCategory = new SubCategory();

$name='';
$errors = [];
if(isset($_GET['id'])){
    $category_id = $_GET['id'];
}


if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    if (empty($name)) {
        $errors['name'] = "* name is required.";
    }
    if (strlen($name) > 100) {
        $errors['name'] = "* name should not exceed 255 characters.";
    }
    if (empty($errors)) {
        $result = $subCategory->create($name, $category_id);

        if ($result === true) {
            $_SESSION['category_create_success'] = true;
            header('Location: ../index.php');
            exit;
        } else {
            echo $result;
        }
    }


}
?>

<div class="form-container">
    <h2>New Sub Category</h2>
    <form method="post">
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Enter name" value="<?= !empty($name) ? $name : '' ?>" >
        <?php if (!empty($errors['name'])): ?>
            <p class="error-text">
                <?= $errors['name']; ?>
            </p>
        <?php endif; ?>

        <button type="submit" name="submit">Create</button>
    </form>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>