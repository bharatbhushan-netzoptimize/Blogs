<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");

$blog = new Blog();
$blogs = $blog->getAllBlogs();

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

?>
<div class="user-profilr">
    <h2>Hi! <?php echo $_SESSION['user_name']?></h2>
    <a href="/blogs-oops/user/edit.php"><button>Edit Profile</button></a>
</div>
<br>



<a href="/blogs-oops/blog/create.php"><button>+New</button></a>
<div>
    <table>
        <thead>
            <th>Heading</th>
            <th>Sub heading</th>
            <th>Content</th>
            <th>Actions</th>
        </thead>
        <tbody>
          
            <?php if (!empty($blogs)) : ?>
                <?php foreach($blogs as $blog): ?>
                    <tr>
                    <td><?=$blog["heading"]?></td>
                    <td><?=$blog["sub_heading"]?></td>
                    <td><?=$blog["content"]?></td>
                    <td>
                    <a href='../blog/edit.php?id=<?=$blog['id']?>'><button>Edit</button></a>
                     | 
                    <button onclick='confirmDelete("<?=$blog["id"]?>")'>Delete</button>
                    </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan='4'>No blogs to show</td></tr>
            <?php endif; ?>
      
        </tbody>
    </table>
    <form method="post">
        <input type="submit" name="logout"
                value="Logout"/>
    </form>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            window.location.href = '../blog/delete.php?id=' + id;
        }
    }
</script>

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>