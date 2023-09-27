<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($name, $email, $password, $confirmPassword)
    {
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            return "All fields are required.";
        }

        $emailCheckSql = "SELECT id FROM users WHERE email = '$email'";
        $emailCheckResult = $this->db->query($emailCheckSql);

        if ($emailCheckResult->num_rows > 0) {
            return "Email address is already registered.";
        }

        if ($password !== $confirmPassword) {
            return "Passwords do not match.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

        if ($this->db->query($sql)) {
            return true;
        } else {
            return "Error during registration: " . $this->db->getError();
        }
    }

    public function login($name, $password)
    {
        $sql = "SELECT * FROM users WHERE email = '$name'";
        $userResult = $this->db->query($sql);

        if ($userResult->num_rows === 1) {
            $userData = $userResult->fetch_assoc(); 
            $hashedPassword = $userData['password'];

            if (password_verify($password, $hashedPassword)) {
                session_start();
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
    }
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit();

    }

    public function getUser($id) {
        $sql = "SELECT * FROM users WHERE id = $id";
        $user = $this->db->query($sql);

        if ($user && $user->num_rows > 0) {
            return $user->fetch_assoc();
        } else {
            return null;
        }
    }

    public function updateUser($id,$newName, $password, $confirmPassword){
        if(empty($newName)){

            return "Name fields is required.";
        }

        if (empty($password) && empty($confirmPassword)) {

            $updateSql = "UPDATE users SET name = '$newName' WHERE id = $id";

            if ($this->db->query($updateSql)) {
                $_SESSION['user_name'] = $newName;

                return true;
            } else {
                return "Error updating name: " . $this->db->getError();
            }

        } elseif (!empty($password) && !empty($confirmPassword) && $password === $confirmPassword) {
    
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET name = '$newName' , password = '$hashedPassword' WHERE id = $id";

            if ($this->db->query($updateSql)) {
                $_SESSION['user_name'] = $newName;
                return true;
            } else {
                return "Error updating name: " . $this->db->getError();
            }
          
        } else {
            return "Password and Confirm Password do not match. Please try again.";
        }
    
    }


}


?>