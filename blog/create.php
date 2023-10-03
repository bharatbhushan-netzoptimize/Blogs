<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/subCategory/SubCategory.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isUser.php");
isUser();

$blog = new Blog();
$category = new Category();
$categories = $category->getAllCategories();
$errors = [];


if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
   
    $subCategory = new SubCategory;
    $subcategories = $subCategory->getSubCategoriesByCategoryId($category_id);
    
    header("Content-Type: application/json");
    echo json_encode($subcategories);
}


if (isset($_POST["submit"])) {
    $heading = $_POST['heading'];
    $subHeading = $_POST['subheading'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $subcategories = $_POST['subcategory'];

    

    if (empty($heading)) {
        $errors['heading'] = "Heading is required";
    }
    if (strlen($heading) > 255) {
        $errors['heading'] = "Heading should not exceed 255 characters.";
    }
    if (empty($subHeading)) {
        $errors['subheading'] = "Sub Heading is required";
    }
    if (strlen($subHeading) > 300) {
        $errors['subheading'] = "Subheading should not exceed 300 characters.";
    }
    if (empty($content)) {
        $errors['content'] = "Content is required";
    }
    if (empty($category)) {
        $errors['category'] = "Please select Category";
    }
    if (empty($subcategories || count($subcategories) === 0)) {
        $errors['subcategory'] = "Please select subcategory";
    }
    if (empty($errors)) {
        $result = $blog->create($heading, $subHeading, $content, $category, $subcategories);

        if ($result === true) {
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
    <h2>New Blog</h2>
    <form method="post">
        <label for="heading">Heading</label>
        <input type="text" name="heading" placeholder="Enter heading" value="<?= !empty($heading) ? $heading : '' ?>">
        <?php if (!empty($errors['heading'])): ?>
            <p class="error-text">
                <?= $errors['heading']; ?>
            </p>
        <?php endif; ?>

        <label for="subheading">Sub Heading</label>
        <input type="text" name="subheading" placeholder="Enter subheading"
            value="<?= !empty($subHeading) ? $subHeading : '' ?>">
        <?php if (!empty($errors['subheading'])): ?>
            <p class="error-text">
                <?= $errors['subheading']; ?>
            </p>
        <?php endif; ?>

        <label for="content">Content</label>
        <textarea name="content" id="" cols="30" rows="10"><?= !empty($content) ? $content : '' ?></textarea>
        <?php if (!empty($errors['content'])): ?>
            <p class="error-text">
                <?= $errors['content']; ?>
            </p>
        <?php endif; ?>

        <label for="category">Select Category</label>
        <select name="category" id="category">
            <option value="">Select Category</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>">
                        <?= $category['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <select name="category" id="category">
                <option value="">No category available</option>
            </select>
        <?php endif; ?>
        <?php if (!empty($errors['category'])): ?>
            <p class="error-text">
                <?= $errors['category']; ?>
            </p>
        <?php endif; ?>

        <label for="subcategory">Select Sub-Categories</label>
        <select name="subcategory[]" id="subcategory" multiple>
            <option value="">Select Subcategory</option>
        </select>
        <?php if (!empty($errors['subcategory'])): ?>
            <p class="error-text">
                <?= $errors['subcategory']; ?>
            </p>
        <?php endif; ?>

        <button type="submit" name="submit">Create</button>
    </form>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById("category");
    const subcategorySelect = document.getElementById("subcategory");

    function updateSubcategories() {
        const selectedCategoryId = categorySelect.value;
        if (!selectedCategoryId) {
            subcategorySelect.innerHTML = '<option value="">No Sub-Category</option>';
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const subcategories = JSON.parse(xhr.responseText);
                        subcategorySelect.innerHTML = '';
                        subcategories.forEach(function(subcategory) {
                            const option = document.createElement("option");
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error("Error parsing JSON response:", error);
                    }
                } else {
                    console.error("Request failed with status:", xhr.status);
                    console.error("Response text:", xhr.responseText);
                }
            }
        };
        xhr.open("GET", `/blogs-oops/category/getSubcategories.php?category_id=${selectedCategoryId}`, true);
        xhr.send();
    }

    categorySelect.addEventListener("change", updateSubcategories);
});
</script>

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>