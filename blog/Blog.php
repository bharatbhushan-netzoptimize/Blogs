<?php
class Blog {
    private $db;
    private $user_id;

    public function __construct($db,$user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function getAllBlogs($user_id) {
        $sql = "SELECT * FROM blogs WHERE user_id = '$user_id' " ;
        return $this->db->query($sql);
    }

    public function create($heading, $subHeading, $content) {
        if (empty($heading) || empty($subHeading) || empty($content)) {
            return "All fields are required.";
        }

        $sql = "INSERT INTO blogs(heading, sub_heading, content, user_id) VALUES ('$heading', '$subHeading', '$content','$this->user_id')";

        if (!$this->db->query($sql)) {
            return "Error while creating the blog due to " . $this->db->getError();
        }

        return true;
    }
    public function delete($id) {
        if (!is_numeric($id)) {
            return "Invalid blog ID.";
        }

        $sql = "DELETE FROM blogs WHERE id = $id AND user_id = $this->user_id";

        if ($this->db->query($sql)) {
            return true;
        } else {
            return "Error deleting record: " . $this->db->getError();
        }
    }

    public function getBlog($id) {
        $sql = "SELECT * FROM blogs WHERE id = $id AND user_id = $this->user_id";
        $blog = $this->db->query($sql);

        if ($blog && $blog->num_rows > 0 ) {
            return $blog->fetch_assoc();
        } else {
            return null;
        }
    }

    public function updateBlog($id, $heading, $subHeading, $content) {
        if (empty($heading) || empty($subHeading) || empty($content)) {
            return "All fields are required.";
        }

        $updateSql = "UPDATE blogs SET heading = '$heading', sub_heading = '$subHeading', content = '$content' WHERE id = '$id'";

        if ($this->db->query($updateSql)) {
            return true;
        } else {
            return "Error updating record: " . $this->db->getError();
        }
    }


}

?>