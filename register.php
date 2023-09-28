<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogout.php");
isLogout();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");

$user = new User();

$errors = []; 
$name = "";
$email = "";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($name) ){
    $errors['name'] = "name field is required";
    } 
    if (empty($email) ){
    $errors['email'] = "email field is required";
    } 
    if (empty($password) ){
    $errors['password'] = "password field is required";
    } 
    if (empty($confirmPassword) ){
    $errors['confirmPassword'] = "confirmPassword field is required";
    } 

    else {
        $result = $user->register($name, $email, $password, $confirmPassword);

        if ($result === true) {
            $_SESSION['user_register_success'] = true;
            header('Location: login.php');
            exit();
        } else {
            $errors['register'] = $result;
        }
    }
}

?>

<div class="form-container">                
    <h2>Register</h2>
    <form method="post">
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Enter name" value="<?= $name ?>" >
        <?php if (!empty($errors['name'])): ?>
            <p class="error-text">
                <?= $errors['name']; ?>
            </p>
        <?php endif; ?>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Enter email" value="<?= $email ?>" >
        <?php if (!empty($errors['email'])): ?>
            <p class="error-text">
                <?= $errors['email']; ?>
            </p>
        <?php endif; ?>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter password" >
        <?php if (!empty($errors['password'])): ?>
            <p class="error-text">
                <?= $errors['password']; ?>
            </p>
        <?php endif; ?>
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" name="confirmPassword" placeholder="Confirm password" >
        <?php if (!empty($errors['confirmPassword'])): ?>
            <p class="error-text">
                <?= $errors['confirmPassword']; ?>
            </p>
        <?php endif; ?>
        <?php if (!empty($errors['register'])) : ?>
            <p class="error-text"><?= $errors['register']; ?></p>
        <?php endif; ?>
        <button type="submit" name="register">SignUp</button>
    </form>
    <a href="login.php"><button>Login</button></a>
</div>

<?php
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/footer.php");
?>