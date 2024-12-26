<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ștergem mai întâi imaginea
    $sql = "SELECT image_path FROM restaurants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }
    
    // Ștergem înregistrarea din baza de date
    $sql = "DELETE FROM restaurants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit();
$conn = new mysqli("localhost", "root", "", "magazin");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // First delete all products associated with the restaurant
    $sql_products = "DELETE FROM products WHERE restaurant_id = ?";
    $stmt = $conn->prepare($sql_products);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Then delete the restaurant
    $sql_restaurant = "DELETE FROM restaurants WHERE id = ?";
    $stmt = $conn->prepare($sql_restaurant);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: index.php");
}
?>
