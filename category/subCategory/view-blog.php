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
    $currentSubCategory = $subCategory->getSubCategory($id);
    $blogsBySubCategory = $blog->getBlogsBySubCategory($id);
    $allSubcategories = $subCategory->getSubCategoriesByCategoryId($currentSubCategory['category_id']);
}
?>
<div class="container">
<div class="blog-heading">
        <?= $currentSubCategory['name'] ?>
    </div>
    <br>
    <?php if(!empty($blogsBySubCategory)): ?>
    <?php foreach ($blogsBySubCategory as $blog) : ?>
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
                    <a href='../../blog/view.php?id=<?= $blog['slug'] ?>' target="_blank">
                        <button class="btn btn-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            View
                        </button>
                    </a>
                </div>
            </div>
            <br>
        <?php endforeach ?>
        <?php else: ?>
        <p>No Blogs available</p>
    <?php endif; ?>
</div>
            
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>