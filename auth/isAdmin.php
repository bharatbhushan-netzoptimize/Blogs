<?php
// include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/user/User.php");
function isAdmin()
{
    $user = new User;
    $privilegeLevel = $user->getUserPrivilege($_SESSION['user_id']);

    if ($privilegeLevel == 2) {
        return true;
    } else {
        ?>
        <script>
            window.location.replace('../user/dashboard.php');
        </script>
        <?php
    }

}

?>