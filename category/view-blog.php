<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/subCategory/SubCategory.php");


$blog = new Blog();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $category = new Category;
    $subCategory = new SubCategory;
    $currentCategory = $category->getCategory($id);
    $blogsByCategory = $blog->getBlogsByCategory($id);
    $currentSubcategories = $subCategory->getSubCategoriesByCategoryId($id);
}

?>
<div class="container">
    <div class="blog-heading">
        <?= $currentCategory['name'] ?>
    </div>
    <br>
    <select class="form-select" id="subcategory-dropdown">
        <option value="" selected>Select Subcategory</option>
        <?php foreach ($currentSubcategories as $subcategory): ?>
            <option value="<?= $subcategory['id'] ?>"
                data-url="subCategory\view-blog.php?id=<?= $subcategory['id'] ?>">
                <?= $subcategory['name'] ?>
            </option>
        <?php endforeach ?>
    </select>
    <div class="container">
        <?php foreach ($blogsByCategory as $blog): ?>
            <div class="card">
                <div class="card-header">
                    <?= $blog['heading'] ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        <?= $blog['sub_heading'] ?>
                    </h5>
                    <p class="card-text">
                        <?= $blog['content'] ?>
                    </p>
                    <a href='../blog/view.php?id=<?= $blog['slug'] ?>' target="_blank">
                        <button class="btn btn-primary"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            View
                        </button>
                    </a>
                </div>
            </div>
            <br>
        <?php endforeach ?>
    </div>
</div>

<script>
    document.getElementById('subcategory-dropdown').addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        var url = selectedOption.getAttribute('data-url');
        if (url) {
            window.location.href = url;
        }
    });
</script>
    <?php
    include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
    ?>