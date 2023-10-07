<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/subCategory/SubCategory.php");
$category = new Category;
$subcategory = new SubCategory;
$categories = $category->getAllCategories();

$selectedCategory = null;
$selecdtedSubcategory = null;
$search = null;
if (isset($_POST["filter"])) {

  $selectedCategory = $_POST['category'];
  $selecdtedSubcategory = $_POST['subcategory'];
  $search = $_POST['search'];
}

?>

<div class="container">
    <?php if (!empty($categories)): ?>
        <div class="list-group">
            <h6 class="list-group-item" aria-current="true">Categories</h6>
            <?php foreach ($categories as $category): ?>
                <header class="list-group-item list-group-item-action active" aria-current="true">
                    <a href="category\view-blog.php?id=<?= $category['id'] ?>" class="nav-link">
                        <?= $category['name'] ?>
                    </a>
                </header>
                <?php $subcategories = $subcategory->getSubCategoriesByCategoryId($category['id']) ?>
                <?php if (!empty($subcategories)): ?>
                    <div class="list-group">
                        <?php foreach ($subcategories as $subcategorydata): ?>
                          <header class="list-group-item list-group-item-action " aria-current="true">
                                <?= $subcategorydata['name'] ?>
                          </header>
                        <?php endforeach ?>
                    </div>
                <?php else: ?>
                    <p>No Subcategories available</p>
                <?php endif; ?>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <p>No Categories available</p>
    <?php endif; ?>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>