<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
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


$blog = new Blog();
$blogs = $blog->filterBlogs($selectedCategory, $selecdtedSubcategory, $search);
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
<nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand">
                <h2>Blogs</h2>
            </a>
            <form method="post">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" placeholder="Enter Heading"
                    value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>">
                <label for="category">Category filter</label>
                <select name="category" id="category">
                    <option value="">Select Category</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <?php $selected = ($category['id'] == $selectedCategory) ? 'selected' : ''; ?>
                            <option value="<?= $category['id'] ?>" <?= $selected ?>>
                                <?= $category['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No category available</option>
                    <?php endif; ?>
                </select>

                <label for="subcategory">Sub-Categories filter</label>
                <select name="subcategory" id="subcategory">
                    <option value="">Select Sub-Category</option>
                </select>
                <button class="btn btn-success" type="submit" name="filter">Apply</button>
                <button class=" btn btn-outline-success"><a href="/blogs-oops/blog/create.php">+New</a></button>
            </form>

        </div>

    </nav>

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
            <?php if (!empty($blogs)): ?>
                <?php foreach ($blogs as $blog): ?>
                    <tr>
                        <td><?= $blog["heading"] ?></td>
                        <td><?= $blog["sub_heading"] ?></td>
                        <td><?= $blog["content"] ?></td>
                        <td><?= $blog["category_name"] ?></td>
                        <td><?= $blog["subcategory_names"] ?></td>
                        <td>
                            <a href='../blog/view.php?id=<?= $blog['slug'] ?>'  target="_blank"><button class="btn btn-secondary">View</button></a>
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
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            window.location.href = '../blog/delete.php?id=' + id;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const categorySelect = document.getElementById("category");
        const subcategorySelect = document.getElementById("subcategory");

        function updateSubcategories() {
            const selectedCategoryId = categorySelect.value;
            if (!selectedCategoryId) {
                subcategorySelect.innerHTML = '<option value="">No Sub-Category</option>';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const subcategories = JSON.parse(xhr.responseText);
                            subcategorySelect.innerHTML = '<option value="">Select Sub-Category</option>';

                            subcategories.forEach(function (subcategory) {
                                const option = document.createElement("option");
                                option.value = subcategory.id;
                                option.textContent = subcategory.name;
                                
                                if (subcategory.id == <?= json_encode($selecdtedSubcategory) ?>) {
                                    option.selected = true;
                                }
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
        updateSubcategories();

        categorySelect.addEventListener("change", updateSubcategories);
    });

</script>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>