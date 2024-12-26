<?php
session_start();

// Conectare la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magazin";

// Crează conexiunea
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifică conexiunea
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

// Verifică dacă datele coșului au fost trimise
if (isset($_POST['cart_data'])) {
    $cart_data = json_decode($_POST['cart_data'], true);
    
    // Salvează datele coșului în baza de date
    foreach ($cart_data as $item) {
        $product_id = $item['id'];
        $product_name = $item['name'];
        $product_price = $item['price'];
        
        $sql = "INSERT INTO cart (product_id, product_name, product_price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isd", $product_id, $product_name, $product_price);
        $stmt->execute();
    }
    
    // Redirecționează către pagina de finalizare a comenzii
    header("Location: ../finalizare_comanda/checkout.php");
    exit();
}
?>