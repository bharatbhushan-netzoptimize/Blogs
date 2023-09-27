<?php
session_start();
include '../auth/isLogin.php';
isLogin();
include '../includes/header.php';
include '../includes/DatabaseConnection.php';
include '../user/User.php';
include '../blog/Blog.php';

$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$blog = new Blog($database,$_SESSION['user_id']);
$blogs = $blog->getAllBlogs($_SESSION['user_id']);
if (isset($_POST["logout"])) {
    $user = new User($database);
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
            <?php
            if ($blogs->num_rows > 0) {
                while ($blog = $blogs->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $blog["heading"] . "</td>";
                    echo "<td>" . $blog["sub_heading"] . "</td>";
                    echo "<td>" . $blog["content"] . "</td>";
                    echo "<td>";
                    echo "<a href='../blog/edit.php?id=" . $blog["id"] . "'><button>Edit</button></a>";
                    echo " | ";
                    echo "<button onclick='confirmDelete(" . $blog["id"] . ")'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No blogs to show</td></tr>";
            }
            ?>
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
include '../includes/footer.php';
?>