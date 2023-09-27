<?php
session_start();
include '../auth/isLogin.php';
isLogin();
include '../includes/header.php';
include '../includes/DatabaseConnection.php';
include '../user/User.php';

$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$userToEdit = new User($database);

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

if(isset($_POST['update'])){

    $newName = $_POST["new_name"];
    $password = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];
    $result = $userToEdit->updateUser($id, $newName, $password, $confirmPassword);

    if ($result === true) {
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        echo $result;
    }
}




?>

<div class="form-container">
    <form  method="post">
        <label for="new_name">Name:</label>
        <input type="text" id="new_name" name="new_name" value="<?php echo $user['name']; ?>" required><br>
        
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password"><br>
        
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br>
        
        <input type="submit" name="update" value="Update">
    </form>
</div>
<?php
include '../includes/footer.php';
?>