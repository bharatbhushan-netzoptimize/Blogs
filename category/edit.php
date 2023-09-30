<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");

$categoryEditor = new Category();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $category = $categoryEditor->getCategory($id);

    if ($category === null) {
        echo "Category not found.";
        exit();
    }
} else {
    echo "Invalid category ID.";
    exit();
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
        $result = $categoryEditor->update($id,$name);

        if ($result === true) {
            $_SESSION['update_success'] = true;
            header('Location: ../category/index.php');
            exit;
        } else {
            echo $result;
        }
    }
}

?>

<div class="form-container">
    <h2>New Category</h2>
    <form method="post">
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Enter name"  value="<?=$category['name'] ?>">
        <?php if (!empty($errors['name'])): ?>
            <p class="error-text" >
                <?= $errors['name']; ?>
            </p>
        <?php endif; ?>

        <button type="submit" name="submit">Update</button>
    </form>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>