<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magazin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];
    
    $target_dir = __DIR__ . "/uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $image_path = "uploads/" . basename($_FILES["image"]["name"]);
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO restaurants (name, address, phone, image_path, description, opening_time, closing_time) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $address, $phone, $image_path, $description, $opening_time, $closing_time);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=1");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>
