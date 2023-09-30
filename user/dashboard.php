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

$selectedCategory =null;
$selecdtedSubcategory = null;
if (isset($_POST["filter"])) {

    $selectedCategory = $_POST['category'];
    $selecdtedSubcategory = $_POST['subcategory'];
}


$blog = new Blog();
$blogs = $blog->filterBlogs($selectedCategory, $selecdtedSubcategory);
?>
<div class="user-profile">
    <h2>Hi! <?php echo $_SESSION['user_name'] ?></h2>
    <a href="/blogs-oops/user/edit.php"><button>Edit Profile</button></a>
</div>

<div class="container">
    <a href="/blogs-oops/blog/create.php"><button>+New</button></a>
    <a href="/blogs-oops/category/index.php"><button>Categories</button></a>

    <div class="filter-container">
        <form method="post">
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

            <button type="submit" name="filter">Apply</button>
        </form>
    </div>

    <table>
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
                        <td><?= $blog["subcategory_name"] ?></td>
                        <td>
                            <a href='../blog/edit.php?id=<?= $blog['id'] ?>'><button>Edit</button></a>
                            |
                            <button onclick='confirmDelete("<?= $blog["id"] ?>")'>Delete</button>
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

    <form method="post">
        <input type="submit" name="logout" value="Logout" />
    </form>
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

            // xhr.open("GET", `/blogs-oops/category/create.php?category_id=${selectedCategoryId}`, true);
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