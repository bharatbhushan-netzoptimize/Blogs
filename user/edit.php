<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");

$userToEdit = new User();

if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $user = $userToEdit->getUser($id);

    if ($user === null) {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid User ID.";
    exit();
}

$errors = [];

if (isset($_POST['update'])) {
    $newName = $_POST["new_name"];
    $password = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if (empty($newName)) {
        $errors['new_name'] = "* Name is required.";
    }
    if (strlen($newName) > 20) {
        $errors['new_name'] = "* Name should not exceed 20 characters ";
    }

    if (!empty($password) || !empty($confirmPassword)) {
        if ($password !== $confirmPassword) {
            $errors['new_password'] = "* Passwords do not match.";
            $errors['confirm_password'] = "* Passwords do not match.";
        }
    }

    if (empty($errors)) {
        $result = $userToEdit->updateUser($id, $newName, $password, $confirmPassword);

        if ($result === true) {
            $_SESSION['update_profile_success'] = true;
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            echo $result;
        }
    }
}
?>
<div class="form-container">
    <form  method="post">
        <label for="new_name">Name:</label>
        <input type="text" id="new_name" name="new_name" value="<?=$user['name'] ?>" >
        <?php if (!empty($errors['new_name'])) : ?>
            <p class="error-text"><?=$errors['new_name'];?></p>
        <?php endif; ?>
        
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password">
        <?php if (!empty($errors['new_password'])) : ?>
            <p class="error-text"><?=$errors['new_password'];?></p>
        <?php endif; ?>
        
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <?php if (!empty($errors['confirm_password'])) : ?>
            <p class="error-text"><?=$errors['confirm_password'];?></p>
        <?php endif; ?>
        
        <input type="submit" name="update" value="Update">
    </form>
</div>
<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>