<?php
session_start();
$conn = new mysqli("localhost", "root", "", "magazin");

if (!isset($_SESSION['driver_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$driver_id = $_SESSION['driver_id'];

$orders = $conn->query("SELECT * FROM comenzi WHERE accepted_by IS NULL OR accepted_by = $driver_id ORDER BY id DESC");
$order_list = [];

while($order = $orders->fetch_assoc()) {
    $order['produse'] = json_decode($order['produse'], true);
    $order_list[] = $order;
}

echo json_encode(['success' => true, 'orders' => $order_list]);
?>