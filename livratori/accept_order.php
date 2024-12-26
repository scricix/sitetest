<?php
session_start();
$conn = new mysqli("localhost", "root", "", "magazin");

if (!isset($_SESSION['driver_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$driver_id = $_SESSION['driver_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    
    // Actualizăm comanda pentru a fi acceptată de livratorul curent
    $stmt = $conn->prepare("UPDATE comenzi SET accepted_by = ? WHERE id = ? AND accepted_by IS NULL");
    $stmt->bind_param("ii", $driver_id, $order_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order already accepted']);
    }
}
?>