<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/subCategory/SubCategory.php");  
$category = new Category;
$categories = $category->getAllCategories();



?>
<div class="accordion" id="accordionPanelsStayOpenExample">
  <h4>Categories</h4>
  <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseCategory<?= $category['id'] ?>" aria-expanded="false"
            aria-controls="collapseCategory<?= $category['id'] ?>">
            <?= $category['name'] ?>
          </button>
        </h2>
        <div id="collapseCategory<?= $category['id'] ?>" class="accordion-collapse collapse"
          data-bs-parent="#accordionPanelsStayOpenExample">
          <div class="accordion-body">
            <h4>Sub-Categories</h4>
            <?php
            // Fetch and display subcategories dynamically
            $subCategory = new SubCategory;
            $subcategories = $subCategory->getSubCategoriesByCategoryId($category['id']);
            if (!empty($subcategories)): ?>
              <?php foreach ($subcategories as $subcategory): ?>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseSubcategory<?= $subcategory['id'] ?>" aria-expanded="false"
                      aria-controls="collapseSubcategory<?= $subcategory['id'] ?>">
                      <?= $subcategory['name'] ?>
                    </button>
                  </h2>
                  <div id="collapseSubcategory<?= $subcategory['id'] ?>" class="accordion-collapse collapse"
                    data-bs-parent="#collapseCategory<?= $category['id'] ?>">
                    <div class="accordion-body">
                    <h4>Blogs</h4>

                      <?php
                      // Fetch and display blogs for the selected subcategory here
                      $blog = new Blog;
                      $blogs = $blog->getBlogsBySubCategory($subcategory['id']);
                      if (!empty($blogs)): ?>

                        <ul class="list-group">
                          <?php foreach ($blogs as $blog): ?>
                            <li class="list-group-item">
                              <a class="list-group-item list-group-item-action"   href="blog/view.php?id=<?= $blog['slug'] ?>"<?= $blog['id'] ?> <?= $blog['id'] ?><?= $blog['id'] ?>  target="_blank">
                                <?= $blog['heading'] ?>
                              </a>
                              <div id="collapseBlog<?= $blog['id'] ?>" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                 <?=$blog['sub_heading']?>
                                </div>
                              </div>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                      <?php else: ?>
                        <p>No blogs available for this subcategory.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No subcategories available</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No category available</p>
  <?php endif; ?>
</div>





<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>