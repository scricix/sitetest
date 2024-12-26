<?php
$conn = new mysqli("localhost", "root", "", "magazin");

$nume_client = $_POST['nume'] . ' ' . $_POST['prenume'];
$telefon = $_POST['telefon'];
$adresa = $_POST['adresa'];
$total = floatval($_POST['total']);
$produse = $_POST['produse'];
$restaurant_name = $_POST['restaurant_id'];
$status = 'În așteptare';
$data_comanda = date('Y-m-d H:i:s');

$sql = "INSERT INTO comenzi (nume_client, telefon, adresa, total, produse, restaurant_name, status, data_comanda) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdssss", 
    $nume_client,    // s - string
    $telefon,        // s - string
    $adresa,         // s - string
    $total,          // d - decimal/double
    $produse,        // s - string
    $restaurant_name, // s - string
    $status,         // s - string
    $data_comanda    // s - string
);


if($stmt->execute()) {
    header('Location: confirmare_comanda.php');
    exit();
} else {
    echo "Eroare la salvarea comenzii: " . $stmt->error;
}
