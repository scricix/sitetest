<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Livrator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    $conn = new mysqli("localhost", "root", "", "magazin");
    
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM drivers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $driver = $result->fetch_assoc();
    ?>
    <div class="container">
        <h2>Editare Livrator</h2>
        <form action="update_driver.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $driver['id']; ?>">
            
            <div class="form-group">
                <label>Nume și Prenume:</label>
                <input type="text" name="name" value="<?php echo $driver['name']; ?>" required>
            </div>

            <div class="form-group">
                <label>Telefon:</label>
                <input type="tel" name="phone" value="<?php echo $driver['phone']; ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $driver['email']; ?>" required>
            </div>

            <div class="form-group">
                <label>Zona de Livrare:</label>
                <select name="delivery_zone" required>
                    <?php
                    $zones = ['Zona 1', 'Zona 2', 'Zona 3'];
                    foreach($zones as $zone) {
                        $selected = ($zone == $driver['delivery_zone']) ? 'selected' : '';
                        echo "<option value='$zone' $selected>$zone</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Vehicul:</label>
                <select name="vehicle" required>
                    <?php
                    $vehicles = ['Mașină', 'Scuter', 'Bicicletă'];
                    foreach($vehicles as $vehicle) {
                        $selected = ($vehicle == $driver['vehicle']) ? 'selected' : '';
                        echo "<option value='$vehicle' $selected>$vehicle</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Poză Profil Curentă:</label>
                <img src="<?php echo $driver['profile_image']; ?>" style="max-width: 200px;">
                <label>Schimbă Poza (opțional):</label>
                <input type="file" name="profile_image" accept="image/*">
            </div>

            <button type="submit">Salvează Modificările</button>
        </form>
    </div>
    <?php } ?>
</body>
</html>
