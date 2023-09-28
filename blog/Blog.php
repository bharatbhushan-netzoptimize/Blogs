<?php
class Blog {
    private $user_id;
    private $pdo;
    
    public function __construct() {
        $this->user_id = $_SESSION['user_id'];
        $this->pdo = DatabaseConnection::createConnection();
    }

    public function getAllBlogs() {

        $sql = "SELECT * FROM blogs WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($heading, $subHeading, $content) {

        $sql = "INSERT INTO blogs(heading, sub_heading, content, user_id) VALUES (:heading, :subHeading, :content, :user_id)";
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':heading',$heading, PDO::PARAM_STR);
            $stmt->bindParam(':subHeading',$subHeading, PDO::PARAM_STR);
            $stmt->bindParam(':content',$content, PDO::PARAM_STR);
            $stmt->bindParam(':user_id',$this->user_id, PDO::PARAM_INT);
            
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            return "Error Creating Blog: " . $e->getMessage();
        }
    }

    public function delete($id) {
        if (!is_numeric($id)) {
            return "Invalid blog ID.";
        }
    
        $sql = "DELETE FROM blogs WHERE id = :id AND user_id = :user_id";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error deleting Blog: " . $e->getMessage();
        }
    }

    public function getBlog($id) {
        if (!is_numeric($id)) {
            return "Invalid blog ID.";
        }

        $sql = "SELECT * FROM blogs WHERE id = :id AND user_id = :user_id";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return "Error while fetching the blog: " . $e->getMessage();
        }
    }

    public function updateBlog($id, $heading, $subHeading, $content) {

        $updateSql = "UPDATE blogs SET heading = :heading, sub_heading = :subHeading, content = :content WHERE id = :id";
        try{
            $stmt =$this->pdo->prepare($updateSql);
            $stmt->bindParam(':heading', $heading, PDO::PARAM_STR );
            $stmt->bindParam(':subHeading', $subHeading, PDO::PARAM_STR );
            $stmt->bindParam(':content', $content, PDO::PARAM_STR );
            $stmt->bindParam(':id', $id, PDO::PARAM_INT );
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            return "Error updating blog". $e->getMessage();
        }
    }


}

?>