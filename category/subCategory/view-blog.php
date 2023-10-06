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
<div class="container-fluid">
    <div class="row">
        <div class="col-1 px-1 bg-light position-fixed" id="sticky-sidebar">
            <div class="nav flex-column flex-nowrap vh-100 overflow-auto text-white p-2">
                <a href="../.." class="nav-link">
                    All Blogs
                </a>
                <div class="list-group">
                    <header class="list-group-item list-group-item-action active" aria-current="true">
                        <?= $currentSubCategory['name'] ?>
                    </header>
                </div>
                <?php if (!empty($allSubcategories)) : ?>
                    <?php foreach ($allSubcategories as $subcategory) : ?>
                        <?php if ($subcategory['id'] != $currentSubCategory['id']) : ?>
                            <a href="view-blog.php?id=<?= $subcategory['id'] ?>" class="nav-link">
                                <?= $subcategory['name'] ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach ?>
                <?php else : ?>
                    <p>No SubCategory available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
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
                    <a href='../blog/view.php?id=<?= $blog['slug'] ?>' target="_blank">
                        <button class="btn btn-primary" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            View
                        </button>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <?php
    include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
    ?>