<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");

$blogEditor = new Blog();
$user = new User();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $blog = $blogEditor->getBlog($id);

    if ($blog === null) {
        echo "Blog post not found.";
        exit();
    }
} else {
    echo "Invalid blog ID.";
    exit();
}
?>
<div class="container  blog-container">
        <div class="blog-heading"><?=$blog['heading']?></div>
        <div class="blog-subheading"><?=$blog['sub_heading']?></div>
        <div class="blog-content">
            <p><?=$blog['content']?></p>
            
        </div>
        <a href="../user/dashboard.php"><button>Back</button></a>
    </div>  
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>
