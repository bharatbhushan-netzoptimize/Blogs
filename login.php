<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogout.php");
isLogout();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");

$user = new User();

$errors = []; 
$username = ""; 

if (isset($_POST['login'])) {
    $username = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors['login'] = "Both email and password are required.";
    } else {
        $result = $user->login($username, $password);

        if ($result === true) {
            header('Location: user/dashboard.php');
            exit();
        } else {
            $errors['login'] = $result;
        }
    }
}
?>

<div class="form-container">
    <?php if (isset($_SESSION['user_register_success']) && $_SESSION['user_register_success']): ?>
            <div class="success-message">User Created Successfully! Please Login</div>

       <?php $_SESSION['user_register_success'] = false; ?>
    <?php endif; ?>
    <h2>Login</h2>
    <form method="post">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Enter email" value="<?= $username ?>" \>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter password" \>
        <?php if (!empty($errors['login'])) : ?>
            <p class="error-text"><?= $errors['login']; ?></p>
        <?php endif; ?>
        <button type="submit" name="login">Log in</button>
    </form>
    <a href="register.php"><button>SignUp</button></a>
</div>

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>
