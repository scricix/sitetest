<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Ștergem poza profilului
    $sql = "SELECT profile_image FROM drivers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $driver = $result->fetch_assoc();
    
    if(file_exists($driver['profile_image'])) {
        unlink($driver['profile_image']);
    }
    
    // Ștergem livratorul din baza de date
    $sql = "DELETE FROM drivers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
?>
