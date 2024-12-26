<?php
$conn = new mysqli("localhost", "root", "", "magazin");

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$sql = "UPDATE comenzi SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $order_id);

$response = ['success' => $stmt->execute()];
echo json_encode($response);
