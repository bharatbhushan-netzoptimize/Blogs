<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isLogin.php");
isLogin();
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/header.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/category/Category.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isUser.php");
include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/auth/isAuthor.php");
isUser();
isAuthor();

$user = new User(); 

if(isset($_POST['allow'])){
    $user->allowUser($_POST['allow']);
}
if(isset($_POST['disallow'])){
    $user->disallowUser($_POST['disallow']);
}

$users = $user->getAllUsers();


?>
<div class="container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Privilege Level</th>
                <th>Allowed</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?= $user["id"] ?>
                        </td>
                        <td>
                            <?= $user["name"] ?>
                        </td>
                        <td>
                            <?= $user["email"] ?>
                        </td>

                        <?php if ($user["level"] == User::USER): ?>
                            <td> <?= $names[0]?> </td>
                        <?php elseif ($user["level"] == User::AUTHOR): ?>
                            <td> <?= $names[1]?> </td>
                        <?php elseif ($user["level"] == User::ADMIN): ?>
                            <td> <?= $names[2]?> </td>
                        <?php else: ?>
                            <td> Unknown Role </td>
                        <?php endif; ?>

                        <?php if ($user["isAllowed"]): ?>
                            <td class="text-success"> Yes </td>
                            <td>
                            <form method="post">
                                <button class="btn btn-danger" name="disallow" value="<?= $user['id'] ?>">Disallow</button>
                            </form>
                            </td>
                        <?php else: ?>
                            <td class="text-danger"> Not Allowed </td>
                            <td>
                                <form method="post">
                                    <button class="btn btn-success" name="allow" value="<?= $user['id'] ?>" >Allow</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan='6'>No user to show</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
