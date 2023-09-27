<?php
session_start();
include 'auth/isLogout.php';
isLogout();
include 'includes/header.php';
include 'includes/DatabaseConnection.php';
include 'user/User.php';

$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$user = new User($database);

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    $result = $user->register($name, $email, $password, $confirmPassword);
 
    if ($result === true) {
        header('Location: login.php');
        exit();
    } else {
        echo $result;
    }
}

?>
<div class="form-container">
    <h2>Register</h2>
    <form  method="post">
        <label for="name">Name</label> 
        <input type="text" name="name" placeholder="Enter name" <?php if (!empty($name)) echo 'value="' . $name . '"'; ?> required>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Enter email" <?php if (!empty($email)) echo 'value="' . $email . '"'; ?>  required>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter password" required>
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" name="confirmPassword" placeholder="Confirm password" required>
        <button type="submit" name="register">SignUp</button>
    </form>
    <a href="login.php"><button>Login</button></a>

</div>
<?php   
   include 'includes/footer.php';
?>
