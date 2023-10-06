<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");

$category = new Category();
$categories = $category->getAllCategories();


$selectedCategory = null;
$selecdtedSubcategory = null;
$search = null;


if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
    echo 'Update successful!';
    $_SESSION['update_success'] = false;
}
if (isset($_SESSION['delete_success']) && $_SESSION['delete_success']) {
    echo 'Deleted successfully!';
    $_SESSION['delete_success'] = false;
}
if (isset($_SESSION['update_profile_success']) && $_SESSION['update_profile_success']) {
    echo 'Profile updated successfully!';
    $_SESSION['update_profile_success'] = false;
}


if (isset($_POST["logout"])) {
    $user = new User();
    $user->logout();
}

if (isset($_POST["filter"])) {

    $selectedCategory = $_POST['category'];
    $selecdtedSubcategory = $_POST['subcategory'];
    $search = $_POST['search'];
}
$blogPerPage = 2;
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
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hi!
            <?php echo $_SESSION['user_name'] ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/blogs-oops/user/edit.php">Edit Profile</a>
                </li>
            </ul>
        </div>
        <form class="d-flex" role="search" method="post">
            <button class="btn btn-outline-danger" name="logout" value="Logout" type="submit">Logout</button>
        </form>
    </div>
</nav>

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
            <?php if (!empty($paginatedBlogs)): ?>
                <?php foreach ($paginatedBlogs as $paginatedBlog): ?>
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
                            <a href='../blog/view.php?id=<?= $paginatedBlog['slug'] ?>' target="_blank"><button
                                    class="btn btn-secondary">View</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan='6'>No blogs to show</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <nav aria-label="...">
        <ul class="pagination">
            <?php if ($currentPage > 1): ?>
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

            for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>
                <li class="page-item disabled"><span class="page-link">...</span></li>
            <?php endif; ?>

            <?php if ($currentPage < $totalPages): ?>
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