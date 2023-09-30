<?php

include($_SERVER["DOCUMENT_ROOT"] . "/blogs-oops/includes/DatabaseConnection.php");

if (isset($_GET['category_id'])) {
    $categoryId = $_GET['category_id'];
    $db = new DatabaseConnection();
    $pdo = $db->createConnection();

    // Replace 'sub_categories' with your actual sub-categories table name
    $query = "SELECT id, name FROM sub_categories WHERE category_id = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":category_id", $categoryId, PDO::PARAM_INT);
    $stmt->execute();
    
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header("Content-Type: application/json");
    echo json_encode($subcategories);
}
?>
