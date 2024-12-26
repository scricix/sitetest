<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurant_id = $_POST['restaurant_id'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];
    $price = $_POST['price'];
    $special_offer = isset($_POST['special_offer']) ? 1 : 0;
    $offer_price = $special_offer ? $_POST['offer_price'] : null;
    
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    
    $sql = "INSERT INTO products (restaurant_id, category, name, ingredients, price, special_offer, offer_price, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdiis", $restaurant_id, $category, $name, $ingredients, $price, $special_offer, $offer_price, $target_file);
    $stmt->execute();
    
    header("Location: add_product.php");
    exit();
}
?>
