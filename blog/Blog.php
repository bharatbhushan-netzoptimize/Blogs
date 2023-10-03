<?php
class Blog
{
    private $user_id;
    private $pdo;

    public function __construct()
    {
        $this->user_id = $_SESSION['user_id'];
        $this->pdo = DatabaseConnection::createConnection();
    }
    public function getOwnBlogs() // own blogs for author
    {
        $sql = "SELECT b.*, c.name AS category_name, GROUP_CONCAT(sc.name) AS subcategory_names
        FROM blogs b
        LEFT JOIN blog_category bc ON b.id = bc.blog_id
        LEFT JOIN categories c ON bc.category_id = c.id
        LEFT JOIN blog_subcategory bs ON b.id = bs.blog_id
        LEFT JOIN sub_categories sc ON bs.subcategory_id = sc.id
        WHERE b.user_id = :user_id
        GROUP BY b.id, b.heading, b.sub_heading, b.content, b.user_id, c.name";


        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllBlogs() // all blogs
    {
        $sql = "SELECT b.id AS id, b.heading, b.sub_heading, b.content, b.user_id, b.slug,
                   c.name AS category_name,
                   GROUP_CONCAT(sc.name) AS subcategory_names
            FROM blogs b
            LEFT JOIN blog_category bc ON b.id = bc.blog_id
            LEFT JOIN categories c ON bc.category_id = c.id
            LEFT JOIN blog_subcategory bs ON b.id = bs.blog_id
            LEFT JOIN sub_categories sc ON bs.subcategory_id = sc.id
            GROUP BY b.id, b.heading, b.sub_heading, b.content, b.user_id, c.name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filterBlogs($categoryId = null, $subcategoryId = null, $search = null)
    {
        $user = new User();
        if ($user->isAuthor()) {
            $blogs = $this->getOwnBlogs();
        } else {
            $blogs = $this->getAllBlogs();
        }
        if (!is_null($categoryId) && $categoryId !== "") {
            $blogs = $this->filterBlogsByCategory($categoryId, $blogs);
        }
        if (!is_null($subcategoryId && $subcategoryId !== "")) {
            $blogs = $this->filterBlogsBySubcategory($subcategoryId, $blogs);
        }
        if (!is_null($search) && $search !== "") {
            $blogs = $this->filterBlogsBySearch($search, $blogs);
        }

        return $blogs;
    }

    public function filterBlogsByCategory($categoryId, $blogs)
    {
        $filteredBlogs = [];

        if (count($blogs) > 0) {
            $sql = "SELECT b.*, c.name AS category_name, s.name AS subcategory_names
            FROM blog_category bc
            JOIN blogs b ON bc.blog_id = b.id
            JOIN categories c ON bc.category_id = c.id
            LEFT JOIN blog_subcategory bs ON b.id = bs.blog_id
            LEFT JOIN sub_categories s ON bs.subcategory_id = s.id
            WHERE c.id = :categoryId";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            $filteredBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $filteredBlogs;
    }

    public function filterBlogsBySubcategory($subcategoryId, $blogs)
    {
        $filteredBlogs = [];

        if (count($blogs) > 0 && is_numeric($subcategoryId)) {
            $sql = "SELECT b.*, c.name AS category_name, s.name AS subcategory_names
                FROM blog_subcategory bs
                JOIN blogs b ON bs.blog_id = b.id
                JOIN sub_categories s ON bs.subcategory_id = s.id
                JOIN categories c ON s.category_id = c.id
                WHERE s.id = :subcategoryId";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':subcategoryId', $subcategoryId, PDO::PARAM_INT);
            $stmt->execute();
            $filteredBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $filteredBlogs;
        }
        return $blogs;
    }
    public function filterBlogsBySearch($search, $blogs)
    {
        $filteredBlogs = array_filter($blogs, function ($blog) use ($search) {
            $heading = strtolower($blog['heading']);
            $subHeading = strtolower($blog['sub_heading']);
            $searchTerm = strtolower($search);

            return strpos($heading, $searchTerm) !== false || strpos($subHeading, $searchTerm) !== false;
        });

        return array_values($filteredBlogs); 
    }
    private function generateSlug($text)
    {
        $slug = preg_replace('/[^A-Za-z0-9]+/', '-', $text);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        $slug .= '-' . $randomString;
        $slug = strtolower($slug);

        return $slug;
    }

    public function create($heading, $subHeading, $content, $category_id, $subcategory_ids)
    {
        $slug = $this->generateSlug($heading);
        $sql = "INSERT INTO blogs(heading, sub_heading, content, user_id, slug) VALUES (:heading, :subHeading, :content, :user_id, :slug)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':heading', $heading, PDO::PARAM_STR);
            $stmt->bindParam(':subHeading', $subHeading, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();

            $blog_id = $this->pdo->lastInsertId();
            $sqlCategory = "INSERT INTO blog_category (blog_id, category_id) VALUES (:blog_id, :category_id)";
            $stmtCategory = $this->pdo->prepare(($sqlCategory));
            $stmtCategory->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);
            $stmtCategory->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmtCategory->execute();

            $sqlSubCategory = "INSERT INTO blog_subcategory (blog_id, subcategory_id) VALUES (:blog_id, :subcategory_id)";
            $stmtSubCategory = $this->pdo->prepare($sqlSubCategory);
            foreach ($subcategory_ids as $subcategory_id) {
                $stmtSubCategory->bindParam(':blog_id', $blog_id, PDO::PARAM_INT);
                $stmtSubCategory->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
                $stmtSubCategory->execute();
            }

            return true;
        } catch (PDOException $e) {
            return "Error Creating Blog: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM blogs WHERE slug = :id ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error deleting Blog: " . $e->getMessage();
        }
    }

    public function getBlog($id)
    {
        $sql = "SELECT * FROM blogs WHERE slug = :id ";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
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

    public function updateBlog($id, $heading, $subHeading, $content)
    {

        $updateSql = "UPDATE blogs SET heading = :heading, sub_heading = :subHeading, content = :content, updated_at = NOW() WHERE slug = :id";
        try {
            $stmt = $this->pdo->prepare($updateSql);
            $stmt->bindParam(':heading', $heading, PDO::PARAM_STR);
            $stmt->bindParam(':subHeading', $subHeading, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error updating blog" . $e->getMessage();
        }
    }
}