<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
$category = new Category;
$categories = $category->getAllCategories();

$selectedCategory = null;
$selecdtedSubcategory = null;
$search = null;
if (isset($_POST["filter"])) {

  $selectedCategory = $_POST['category'];
  $selecdtedSubcategory = $_POST['subcategory'];
  $search = $_POST['search'];
}
$blogPerPage = 3;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$blog = new Blog();
$blogs = $blog->filterBlogs($selectedCategory, $selecdtedSubcategory, $search);
$paginatedBlogs = $blog->paginateBlogs($blogs, $currentPage, $blogPerPage);

$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$totalPages = ceil(count($blogs) / $blogPerPage);

if ($currentPage < 1) {
  $currentPage = 1;
} elseif ($currentPage > $totalPages) {
  $currentPage = $totalPages;
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-1 px-1 bg-light position-fixed" id="sticky-sidebar">
      <div class="nav flex-column flex-nowrap vh-100 overflow-auto text-white p-2">
        <div class="list-group">
          <header class="list-group-item list-group-item-action active" aria-current="true">
            All Blogs
          </header>
        </div>
        <?php if (!empty($categories)) : ?>
          <div class="list-group">
            <h6 class="list-group-item  " aria-current="true">Categories</h6>
            <?php foreach ($categories as $category) : ?>
              <a href="category\view-blog.php?id=<?= $category['id'] ?>" class="nav-link">
                <?= $category['name'] ?>
              </a>
            <?php endforeach ?>
          </div>
        <?php else : ?>
          <p>No SubCategory available</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="container">
    <?php
    include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/filterbar.php");
    ?>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Heading</th>
          <th>Sub heading</th>
          <th>Content</th>
          <th>Category</th>
          <th>Sub-Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($paginatedBlogs)) : ?>
          <?php foreach ($paginatedBlogs as $paginatedBlog) : ?>
            <tr>
              <td>
                <?= $paginatedBlog["heading"] ?>
              </td>
              <td>
                <?= $paginatedBlog["sub_heading"] ?>
              </td>
              <td>
                <?= $paginatedBlog["content"] ?>
              </td>
              <td>
                <?= $paginatedBlog["category_name"] ?>
              </td>
              <td>
                <?= $paginatedBlog["subcategory_names"] ?>
              </td>
              <td>
                <a href='../blog/view.php?id=<?= $paginatedBlog['slug'] ?>' target="_blank"><button class="btn btn-secondary">View</button></a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan='6'>No blogs to show</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <nav aria-label="...">
      <ul class="pagination">
        <?php if ($currentPage > 1) : ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
              <span aria-hidden="true">Previous</span>
            </a>
          </li>
        <?php endif; ?>

        <?php
        $maxPagesToShow = 5;
        $halfMax = floor($maxPagesToShow / 2);
        $startPage = max(1, $currentPage - $halfMax);
        $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

        if ($startPage > 1) {
          echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) :
        ?>
          <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $i; ?>">
              <?php echo $i; ?>
            </a>
          </li>
        <?php endfor; ?>

        <?php if ($endPage < $totalPages) : ?>
          <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>

        <?php if ($currentPage < $totalPages) : ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
              <span aria-hidden="true">Next</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
  <?php
  include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/script.php");
  include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
  ?>