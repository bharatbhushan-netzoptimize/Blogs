<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/blog/Blog.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isUser.php");

isUser();
$blogEditor = new Blog();
$user = new User;   
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $blog = $blogEditor->getBlogWithImages($id);

    if($user->isAuthor()){
        if($blog['user_id']!=$_SESSION['user_id']){
            echo "Invalid blog ID.";
            exit();
        }
    }
    

    if ($blog === null) {
        echo "Blog post not found.";
        exit();
    }
} else {
    echo "Invalid blog ID.";
    exit();
}

if (isset($_POST['update'])) {
    $newHeading = $_POST['heading'];
    $newSubHeading = $_POST['subheading'];
    $newContent = $_POST['content'];
    $new_images = isset($_FILES['new_images']) ? $_FILES['new_images'] : array();


    if (isset($_POST['remove_images'])) {
        $removedImages = $_POST['remove_images'];
        $blogEditor->removeImageFromBlog($id,$removedImages);
    }
    if (empty($newHeading)) {
        $errors['heading'] = "Heading is required.";
    }
    if (strlen($newHeading) > 255) {
        $errors['heading'] = "Heading should not exceed 255 characters.";
    }
    if (empty($newSubHeading)) {
        $errors['subheading'] = "Sub Heading is required.";
    }
    if (strlen($newSubHeading) > 300) {
        $errors['subheading'] = "Subheading should not exceed 300 characters.";
    }
    if (empty($newContent)) {
        $errors['content'] = "Content is required.";
    }
    if (empty($errors)) {
        $result = $blogEditor->updateBlog($id, $newHeading, $newSubHeading, $newContent, $new_images);
        if ($result === true) {
            $_SESSION['update_success'] = true;
            ?>
            <script>
                window.location.replace('../user/dashboard.php');
            </script>
            <?php
            exit;
        } else {
            echo $result;
        }
    }
}
?>
<div class="form-container">
    <h2>Edit Blog Post</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="heading">Heading:</label>
        <input type="text" name="heading" value="<?=$blog['heading']?>">
        <?php if (!empty($errors['heading'])): ?>
            <p class="error-text">
                <?= $errors['heading']; ?>
            </p>
        <?php endif; ?>
        <label for="subheading">Sub Heading:</label>
        <input type="text" name="subheading" value="<?=$blog['sub_heading']?>">
        <?php if (!empty($errors['subheading'])): ?>
            <p class="error-text">
                <?= $errors['subheading']; ?>
            </p>
        <?php endif; ?>

        <label for="content">Content:</label>
        <textarea name="content" cols="30" rows="10"><?=$blog['content']?></textarea>
        <?php if (!empty($errors['content'])): ?>
            <p class="error-text">
                <?= $errors['content']; ?>
            </p>
        <?php endif; ?>

        <label for="images">Images</label>
        <div class="blog-images text-center">
            <?php if (!empty($blog['images'])): ?>
                <?php foreach ($blog['images'] as $imagePath): ?>
                    <img src="<?= $imagePath ?>" class="img-thumbnail" alt="Blog Image">
                    <input type="checkbox" name="remove_images[]" value="<?= $imagePath ?>"> Remove
                <?php endforeach; ?>
            <?php else: ?>
                No Images Available
            <?php endif; ?>
        </div>
        
        <label for="new_images">Upload Image</label>
        <input type="file" name="new_images[]" id="images"  accept="image/*" multiple>
        <div id="selectedImagesContainer"></div>
        <div id="imageError" class="error-text"></div>
    
        <button type="submit" name="update">Update</button>
    </form>
    <a href="../user/dashboard.php"><button>Cancel</button></a>

</div>
<script>
    function updateSelectedImages() {
        const selectedImagesContainer = document.getElementById('selectedImagesContainer');
        const imagesInput = document.getElementById('images');
        selectedImagesContainer.innerHTML = ''; // Clear previous content

        for (let i = 0; i < imagesInput.files.length; i++) {
            const image = imagesInput.files[i];
            const imageElement = document.createElement('img');
            imageElement.src = URL.createObjectURL(image);
            imageElement.style.width = '150px'; 
            imageElement.style.height = '150px'; 
            imageElement.style.margin = '5px'; 

            imageElement.className = 'selected-image';
            selectedImagesContainer.appendChild(imageElement);
        }
        validateImages();
    }

    const imagesInput = document.getElementById('images');
    imagesInput.addEventListener('change', updateSelectedImages);



    function validateImages() {
        const imagesInput = document.getElementById('images');
        const selectedImagesContainer = document.getElementById('selectedImagesContainer');
        const errorContainer = document.getElementById('imageError');
        const maxFileSize = 5 * 1024 * 1024; 
        
        errorContainer.innerHTML = ''; 

        for (let i = 0; i < imagesInput.files.length; i++) {
            const image = imagesInput.files[i];
            const allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];
            const extension = image.name.split('.').pop().toLowerCase();

            if (allowedExtensions.indexOf(extension) === -1) {
                errorContainer.innerHTML = 'Invalid file type. Only JPG, JPEG, PNG, and HEIC files are allowed.';
                imagesInput.value = ''; 
                selectedImagesContainer.innerHTML = ''; 
                return false;
            }

            if (image.size > maxFileSize) {
                errorContainer.innerHTML = 'File size exceeds the maximum limit of 5 MB.';
                imagesInput.value = ''; 
                selectedImagesContainer.innerHTML = ''; 
                return false; 
            }
        }
        return true; 
    }
    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        if (!validateImages()) {
            event.preventDefault(); 
        }
    });


</script>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>