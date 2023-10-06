<?php
    function isUser(){
    $user = new User;
    
        if($user->isUser()){
            ?>
            <script>
                window.location.replace('../index.php');
            </script>
            <?php
        }
    }
?>