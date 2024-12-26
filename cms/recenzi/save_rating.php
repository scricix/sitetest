<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    
    // Verifică dacă există deja un rating pentru acest produs
    $sql = "SELECT id FROM product_ratings WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        // Actualizează ratingul existent
        $sql = "UPDATE product_ratings SET rating = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $rating, $product_id);
    } else {
        // Inserează un rating nou
        $sql = "INSERT INTO product_ratings (product_id, rating) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $product_id, $rating);
    }
    
    $stmt->execute();
}

header("Location: index.php");
exit();
?>
