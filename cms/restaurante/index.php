<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<a href="../index.html" class="back-arrow">
    <svg viewBox="0 0 24 24" width="24" height="24">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
    </svg>
</a>

<body>
    <div class="container">
        <h1>Add New Restaurant</h1>
        <form id="restaurantForm" action="add_restaurant.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Restaurant Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group time-inputs">
                <div>
                    <label for="opening_time">Opening Time:</label>
                    <input type="time" id="opening_time" name="opening_time" required>
                </div>
                <div>
                    <label for="closing_time">Closing Time:</label>
                    <input type="time" id="closing_time" name="closing_time" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Restaurant Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            
            <button type="submit">Add Restaurant</button>
        </form>

        <div class="restaurants-list">
            <h2>Restaurants List</h2>
            <?php
            $conn = new mysqli("localhost", "root", "", "magazin");
            
            $sql = "SELECT * FROM restaurants";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="restaurant-card">';
                    echo '<img src="'.$row["image_path"].'" alt="'.$row["name"].'">';
                    echo '<h3>'.$row["name"].'</h3>';
                    echo '<p><strong>Adresa:</strong> '.$row["address"].'</p>';
                    echo '<p><strong>Telefon:</strong> '.$row["phone"].'</p>';
                    echo '<p><strong>Program:</strong> '.$row["opening_time"].' - '.$row["closing_time"].'</p>';
                    echo '<p>'.$row["description"].'</p>';
                    echo '<div class="action-buttons">';
                    echo '<a href="edit.php?id='.$row["id"].'" class="btn edit">Editează</a>';
                    echo '<a href="delete.php?id='.$row["id"].'" class="btn delete">Șterge</a>';
                    echo '<form method="POST" action="toggle_status.php" style="display: inline;">';
                    echo '<input type="hidden" name="id" value="'.$row["id"].'">';
                    echo '<button type="submit" class="btn '.($row["is_available"] ? 'available' : 'unavailable').'">';
                    echo $row["is_available"] ? 'Disponibil' : 'Indisponibil';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nu există restaurante în baza de date.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
