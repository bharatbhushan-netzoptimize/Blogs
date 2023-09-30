<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/subCategory/SubCategory.php");

$category = new Category();
$categories = $category->getAllCategories();

if (isset($_SESSION['category_create_success']) && $_SESSION['category_create_success']) {
    echo 'Created successfully!';
    $_SESSION['category_create_success'] = false;
}
if (isset($_SESSION['delete_success']) && $_SESSION['delete_success']) {
    echo 'Deleted successfully!';
    $_SESSION['delete_success'] = false;
}
if (isset($_SESSION['update_success']) && $_SESSION['update_success']) {
    echo 'Update successful!';
    $_SESSION['update_success'] = false;
}
?>
<br>
<a href="/blogs-oops/category/create.php"><button>+New</button></a>
<div>
    <table>
        <thead>
            <th>Categories</th>
            <th>Sub Categories</th>
            <th>Actions</th>
        </thead>
        <tbody>
          
            <?php if (!empty($categories)) : ?>
                <?php foreach($categories as $category): ?>
                    <tr>
                        <td><?=$category["name"]?></td>
                        <td>
                            <?php
                            // Fetch subcategories for the current category
                            $subCategory = new SubCategory();
                            $subCategories = $subCategory->getSubCategoriesByCategoryId($category['id']);
                            if (!empty($subCategories)) {
                                foreach ($subCategories as $subCategory) {
                                    echo $subCategory["name"] . "<br>";
                                }
                            } else {
                                echo "No subcategories";
                            }
                            ?>
                        </td>
                        <td>
                        <a href='../category/subCategory/create.php?id=<?=$category['id']?>'><button>Add Sub Category</button></a>
                        |
                        <a href='../category/edit.php?id=<?=$category['id']?>'><button>Edit</button></a>
                        |
                        <button onclick='confirmDelete("<?=$category["id"]?>")'>Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan='4'>No category to show</td></tr>
            <?php endif; ?>
      
        </tbody>
    </table>
</div>




<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            window.location.href = '../category/delete.php?id=' + id;
        }
    }
</script>

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>