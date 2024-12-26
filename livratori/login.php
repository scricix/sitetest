<?php
session_start();
$conn = new mysqli("localhost", "root", "", "magazin");

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    
    // Folosim declarații pregătite pentru a preveni SQL injection
    $sql = "SELECT * FROM drivers WHERE name = ? AND phone = ? AND status = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['driver_id'] = $row['id'];
        $_SESSION['driver_name'] = $row['name'];
        $_SESSION['driver_image'] = $row['profile_image'];
        
        header("Location: index.php");
        exit();
    } else {
        $error = 'Nume sau telefon incorect sau cont inactiv.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Livratori</title>
    <style>
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            background: #f0f2f5; 
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #2196F3;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #1976D2;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Login Livratori</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <input type="text" name="name" placeholder="Nume" required>
        <input type="text" name="phone" placeholder="Telefon" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>