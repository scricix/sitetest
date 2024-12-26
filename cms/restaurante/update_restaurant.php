<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];
    
    if (!empty($_FILES["new_image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["new_image"]["name"]);
        
        // Ștergem imaginea veche
        $sql = "SELECT image_path FROM restaurants WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row && file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
        
        move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file);
        
        $sql = "UPDATE restaurants SET name=?, address=?, phone=?, description=?, opening_time=?, closing_time=?, image_path=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $name, $address, $phone, $description, $opening_time, $closing_time, $target_file, $id);
    } else {
        $sql = "UPDATE restaurants SET name=?, address=?, phone=?, description=?, opening_time=?, closing_time=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $address, $phone, $description, $opening_time, $closing_time, $id);
    }
    
    $stmt->execute();
}

header("Location: index.php");
exit();
?>
