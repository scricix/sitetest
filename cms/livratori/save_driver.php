<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $delivery_zone = $_POST['delivery_zone'];
    $vehicle = $_POST['vehicle'];
    $status = 1; // activ by default

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO drivers (name, phone, email, delivery_zone, vehicle, profile_image, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $phone, $email, $delivery_zone, $vehicle, $target_file, $status);
    $stmt->execute();
}

header("Location: index.php");
?>
