<?php
class Blog {
    private $user_id;
    private $pdo;
    
    public function __construct() {
        $this->user_id = $_SESSION['user_id'];
        $this->pdo = DatabaseConnection::createConnection();
    }

    public function getAllBlogs() {
        $sql = "SELECT b.*, c.name AS category_name, sc.name AS subcategory_name
                FROM blogs b
                LEFT JOIN blog_category bc ON b.id = bc.blog_id
                LEFT JOIN categories c ON bc.category_id = c.id
                LEFT JOIN blog_subcategory bs ON b.id = bs.blog_id
                LEFT JOIN sub_categories sc ON bs.subcategory_id = sc.id
                WHERE b.user_id = :user_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filterBlogs($categoryId = null, $subcategoryId = null, $page = 1, $perPage = 10) {
        print_r($categoryId);
        print_r($subcategoryId);

        $blogs = $this->getAllBlogs();
        if (!is_null($categoryId)) {
            $blogs = $this->filterBlogsByCategory($categoryId,$blogs);
        }
        if (!is_null($subcategoryId && !empty($subcategoryId))) {
            $blogs = $this->filterBlogsBySubcategory($subcategoryId,$blogs);
        }




    //        echo "<pre>";
    //        echo"function";
    // print_r($blogs);
 
    // echo "</pre>";
    // die();
        return $blogs;
    }

    private function paginateBlogs($blogs, $offset, $perPage) {

        $paginatedBlogs = [];

        if (count($blogs) > 0) {

            $sql = "SELECT * FROM blogs LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();

            $paginatedBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $paginatedBlogs;
        
    }

   public function filterBlogsByCategory($categoryId, $blogs) {
    $filteredBlogs = [];

    if (count($blogs) > 0) {
         $sql = 
        //  "SELECT b.*, c.name AS category_name
        //         FROM blog_category bc
        //         JOIN blogs b ON bc.blog_id = b.id
        //         JOIN categories c ON bc.category_id = c.id
        //         WHERE c.id = :categoryId";
                  $sql = "SELECT b.*, c.name AS category_name
                  FROM blog_category bc
                  JOIN blogs b ON bc.blog_id = b.id
                  JOIN categories c ON bc.category_id = c.id
                  WHERE c.id = :categoryId";
  


        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        $filteredBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    // echo "<pre>";
    // print_r($filteredBlogs);
    // echo "</pre>";
    // die();
    }

    return $filteredBlogs;
   }

   public function filterBlogsBySubcategory($subcategoryId, $blogs) {
    $filteredBlogs = [];

    if (count($blogs) > 0) {
        $sql = "SELECT b.*, c.name AS category_name, s.name AS subcategory_name
                FROM blog_subcategory bs
                JOIN blogs b ON bs.blog_id = b.id
                JOIN sub_categories s ON bs.subcategory_id = s.id
                JOIN categories c ON s.category_id = c.id
                WHERE s.id = :subcategoryId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':subcategoryId', $subcategoryId, PDO::PARAM_INT);
        $stmt->execute();
        $filteredBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $filteredBlogs;
}









    public function create($heading, $subHeading, $content, $category_id, $subcategory_ids) {
        $sql = "INSERT INTO blogs(heading, sub_heading, content, user_id) VALUES (:heading, :subHeading, :content, :user_id)";
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':heading',$heading, PDO::PARAM_STR);
            $stmt->bindParam(':subHeading',$subHeading, PDO::PARAM_STR);
            $stmt->bindParam(':content',$content, PDO::PARAM_STR);
            $stmt->bindParam(':user_id',$this->user_id, PDO::PARAM_INT);
            $stmt->execute();

            $blog_id = $this->pdo->lastInsertId();
            $sqlCategory = "INSERT INTO blog_category (blog_id, category_id) VALUES (:blog_id, :category_id)";
            $stmtCategory = $this->pdo->prepare(($sqlCategory));
            $stmtCategory->bindParam(':blog_id', $blog_id  ,PDO::PARAM_INT);
            $stmtCategory->bindParam(':category_id',  $category_id ,PDO::PARAM_INT);
            $stmtCategory->execute();

            $sqlSubCategory = "INSERT INTO blog_subcategory (blog_id, subcategory_id) VALUES (:blog_id, :subcategory_id)";
            $stmtSubCategory = $this->pdo->prepare($sqlSubCategory);
            foreach ($subcategory_ids as $subcategory_id) {
                $stmtSubCategory->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);
                $stmtSubCategory->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
                $stmtSubCategory->execute();
            }

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
        // $sql = "UPDATE blogs SET deleted_at = NOW() WHERE id = :id AND user_id = :user_id";

    
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

        $updateSql = "UPDATE blogs SET heading = :heading, sub_heading = :subHeading, content = :content, updated_at = NOW() WHERE id = :id";
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