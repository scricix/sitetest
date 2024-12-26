<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "UPDATE products SET special_offer = NOT special_offer WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: add_product.php");
exit();
?>
