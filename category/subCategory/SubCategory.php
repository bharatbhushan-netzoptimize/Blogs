<?php

class SubCategory
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::createConnection();
    }
    public function getAllSubCategories()
    {

        $sql = "SELECT * FROM sub_categories";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($name, $category_id)
    {

        $sql = "INSERT INTO sub_categories(name,category_id) VALUES (:name, :category_id)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error Creating Category: " . $e->getMessage();
        }
    }
    public function getSubCategoriesByCategoryId($category_id)
    {
        $sql = "SELECT * FROM sub_categories where category_id =:category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function getSubCategory($id)
    {
        if (!is_numeric($id)) {
            return "Invalid Category ID.";
        }

        $sql = "SELECT * FROM sub_categories WHERE id = :id";

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



}