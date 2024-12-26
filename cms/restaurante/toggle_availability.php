<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magazin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "UPDATE restaurants SET is_available = NOT is_available WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: index.html");
}

$conn->close();
?>
