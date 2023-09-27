<?php
session_start();
include 'auth/isLogout.php';
isLogout();
include 'includes/header.php';
include 'includes/DatabaseConnection.php';
include 'user/User.php';


$database = new DatabaseConnection('localhost:3301', 'root', '', 'blogs');
$user = new User($database);

if (isset($_POST['login'])) {
    $username = $_POST['email'];
    $password = $_POST['password'];

    $result = $user->login($username, $password);

    if ($result === true) {
        header('Location: user/dashboard.php');
        exit();
    } else {
        echo $result;
    }
}
?>


<div class="form-container">
    <h2>Login</h2>
    <form  method="post">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Enter email" <?php if (!empty($username)) echo 'value="' . $username . '"'; ?> required>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit" name="login">Log in</button>
    </form>
    <a href="register.php"><button>SignUp</button></a>
</div>



<?php   
   include 'includes/footer.php';
?>
