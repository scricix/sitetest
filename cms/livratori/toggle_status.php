<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Comutăm statusul între activ/inactiv
    $sql = "UPDATE drivers SET status = NOT status WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
?>
