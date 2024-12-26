<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $restaurant_id = $_POST['restaurant_id'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];
    $price = $_POST['price'];
    $special_offer = isset($_POST['special_offer']) ? 1 : 0;
    $offer_price = $special_offer ? $_POST['offer_price'] : null;
    
    if(!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        
        $sql = "UPDATE products SET restaurant_id=?, category=?, name=?, ingredients=?, 
                price=?, special_offer=?, offer_price=?, image_path=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssdissi", $restaurant_id, $category, $name, $ingredients, 
                         $price, $special_offer, $offer_price, $target_file, $id);
    } else {
        $sql = "UPDATE products SET restaurant_id=?, category=?, name=?, ingredients=?, 
                price=?, special_offer=?, offer_price=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssdisi", $restaurant_id, $category, $name, $ingredients, 
                         $price, $special_offer, $offer_price, $id);
    }
    
    $stmt->execute();
}

header("Location: add_product.php");
exit();
?>
