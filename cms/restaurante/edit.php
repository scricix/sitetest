<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM restaurants WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $restaurant = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Restaurant</h1>
        <form action="update_restaurant.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $restaurant['id']; ?>">
            
            <div class="form-group">
                <label for="name">Restaurant Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $restaurant['name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $restaurant['address']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $restaurant['phone']; ?>" required>
            </div>

            <div class="form-group time-inputs">
                <div>
                    <label for="opening_time">Opening Time:</label>
                    <input type="time" id="opening_time" name="opening_time" value="<?php echo $restaurant['opening_time']; ?>" required>
                </div>
                <div>
                    <label for="closing_time">Closing Time:</label>
                    <input type="time" id="closing_time" name="closing_time" value="<?php echo $restaurant['closing_time']; ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Current Image:</label>
                <img src="<?php echo $restaurant['image_path']; ?>" style="max-width: 200px;">
                <label for="new_image">Change Image:</label>
                <input type="file" id="new_image" name="new_image" accept="image/*">
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"><?php echo $restaurant['description']; ?></textarea>
            </div>
            
            <button type="submit">Update Restaurant</button>
        </form>
    </div>
</body>
</html>
