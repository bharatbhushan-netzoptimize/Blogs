<?php
class User
{
    private $pdo;
    
    const USER = 0;
    const AUTHOR = 1;
    const ADMIN = 2;
    
    public function __construct()
    {
        $this->pdo = DatabaseConnection::createConnection();
    }

    public function register($name, $email, $password, $confirmPassword)
    {
        $emailCheckSql = "SELECT id FROM users WHERE email = :email";
        try {
            $stmt = $this->pdo->prepare($emailCheckSql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return "Email address is already registered.";
            }

            if ($password !== $confirmPassword) {
                return "Passwords do not match.";
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
            $stmt = $this->pdo->prepare($insertSql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return true;
            } else {
                return "Error during registration: " . $stmt->errorInfo()[2];
            }
        } catch (PDOException $e) {
            return "Error during registration: " . $e->getMessage();
        }
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                if($userData['is_allowed']==0){
                    return "!Not allowed to login";
                }
                $hashedPassword = $userData['password'];

                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['user_name'] = $userData['name'];
                    $_SESSION['email'] = $userData['email'];

                    return true;
                } else {
                    return "Invalid password.";
                }
            } else {
                return "User not found.";
            }
        } catch (PDOException $e) {
            return "Error during login: " . $e->getMessage();
        }
    }
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        ?>
        <script>
            window.location.replace('/blogs-oops/login.php');
        </script>
        <?php
        exit();

    }

    public function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return "Error while finding the user: " . $e->getMessage();
        }
    }


    public function getUserPrivilege($id){

        $user=$this->getUser($id);
        return $user['level'];

    }

    
    public function updateUser($id, $newName, $password, $confirmPassword)
    {


        if (empty($password) && empty($confirmPassword)) {
            $sql = "UPDATE users SET name = :newName, updated_at = NOW()  WHERE id = :id";

            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['user_name'] = $newName;
                return true;
            } catch (PDOException $e) {
                return "Error updating name: " . $e->getMessage();
            }
        } elseif (!empty($password) && !empty($confirmPassword) && $password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = :newName, password = :hashedPassword WHERE id = :id";

            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
                $stmt->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $_SESSION['user_name'] = $newName;
                return true;
            } catch (PDOException $e) {
                return "Error updating name and password: " . $e->getMessage();
            }
        } else {
            return "Password and Confirm Password do not match. Please try again.";
        }
    }

    function getAllUsers(){

        $sql = "SELECT * FROM users where id != :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    function allowUser($userId){
        $sql = "Update users set is_allowed = 1 where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
    function disallowUser($userId){
        $sql = "Update users set is_allowed = 0 where id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    function isAdmin(){
        $privilegeLevel = $this->getUserPrivilege( $_SESSION['user_id']);
    
        if($privilegeLevel == 2){
            return true;
        }else{
            return false;
        }
    }
    function isUser(){
        $privilegeLevel = $this->getUserPrivilege( $_SESSION['user_id']);
    
        if($privilegeLevel == 0){
            return true;
        }else{
            return false;
        }
    }
    function isAuthor(){
        $privilegeLevel = $this->getUserPrivilege( $_SESSION['user_id']);
        if($privilegeLevel == 1){
            return true;
        }else{
            return false;
        }
    }

}
?>