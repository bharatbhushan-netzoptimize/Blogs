<?php
class Category {
    // private $user_id;
    private $pdo;
    
    public function __construct() {
        // $this->user_id = $_SESSION['user_id'];
        $this->pdo = DatabaseConnection::createConnection();
    }

    public function getAllCategories() {

        $sql = "SELECT * FROM categories";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($name) {

        $sql = "INSERT INTO categories(name) VALUES (:name)";
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name',$name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            return "Error Creating Category: " . $e->getMessage();
        }
    }
    public function getCategory($id) {
        if (!is_numeric($id)) {
            return "Invalid Category ID.";
        }

        $sql = "SELECT * FROM categories WHERE id = :id";
    
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
            return "Error while fetching the category: " . $e->getMessage();
        }
    }
    public function update($id,$name) {

        $updateSql = "UPDATE categories SET name = :name WHERE id = :id";
        try{
            $stmt =$this->pdo->prepare($updateSql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR );
            $stmt->bindParam(':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            return "Error updating category". $e->getMessage();
        }
    }

    public function delete($id) {
        if (!is_numeric($id)) {
            return "Invalid blog ID.";
        }
    
        $sql = "DELETE FROM categories WHERE id = :id ";
        // $sql = "UPDATE blogs SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id";

    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error deleting category: " . $e->getMessage();
        }
    }







}


?>