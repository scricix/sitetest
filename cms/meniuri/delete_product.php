<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ștergem imaginea asociată
    $sql = "SELECT image_path FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if(file_exists($product['image_path'])) {
        unlink($product['image_path']);
    }
    
    // Ștergem produsul din baza de date
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: add_product.php");
exit();
?>
