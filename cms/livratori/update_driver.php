<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $delivery_zone = $_POST['delivery_zone'];
    $vehicle = $_POST['vehicle'];
    
    if(!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
        
        $sql = "UPDATE drivers SET name=?, phone=?, email=?, delivery_zone=?, 
                vehicle=?, profile_image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $name, $phone, $email, $delivery_zone, 
                         $vehicle, $target_file, $id);
    } else {
        $sql = "UPDATE drivers SET name=?, phone=?, email=?, delivery_zone=?, 
                vehicle=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $phone, $email, $delivery_zone, 
                         $vehicle, $id);
    }
    
    $stmt->execute();
}

header("Location: index.php");
?>
