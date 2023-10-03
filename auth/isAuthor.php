<?php
function isAuthor()
{
    $user = new User;
    if ($user->isAuthor()) {
        ?>
        <script>
            window.location.replace('../author/dashboard.php');
        </script>
        <?php
    }
}
?>