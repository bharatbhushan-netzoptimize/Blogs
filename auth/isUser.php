<?php
    function isUser(){
    $user = new User;
    
        if($user->isUser()){
            ?>
            <script>
                window.location.replace('../blog/index.php');
            </script>
            <?php
        }
    }
?>