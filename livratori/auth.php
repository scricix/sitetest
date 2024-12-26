<?php
session_start();
$conn = new mysqli("localhost", "root", "", "magazin");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("SELECT id, name, profile_image FROM drivers WHERE name = ? AND phone = ? AND status = 1");
    $stmt->bind_param("ss", $name, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($driver = $result->fetch_assoc()) {
        $_SESSION['driver_id'] = $driver['id'];
        $_SESSION['driver_name'] = $driver['name'];
        $_SESSION['driver_image'] = $driver['profile_image'];
        header('Location: index.php');
        exit();
    }
    
    header('Location: login.php?error=1');
    exit();
}
