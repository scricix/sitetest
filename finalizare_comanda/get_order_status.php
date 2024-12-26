<?php
$conn = new mysqli("localhost", "root", "", "magazin");
$order_id = $_GET['order_id'];

$result = $conn->query("SELECT status FROM comenzi WHERE id = $order_id");
$status = $result->fetch_assoc();

echo json_encode(['status' => $status['status']]);
