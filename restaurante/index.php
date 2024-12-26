<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="../index.html" class="back-arrow">
            <svg viewBox="0 0 24 24" width="24" height="24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
        </a>

        <div class="restaurants-grid">
            <?php
            $conn = new mysqli("localhost", "root", "", "magazin");
            $sql = "SELECT * FROM restaurants ORDER BY name";
            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
                echo '<div class="restaurant-card">';
                echo '<div class="restaurant-image">';
                echo '<img src="../cms/restaurante/' . $row["image_path"] . '" alt="' . $row["name"] . '">';
                echo '</div>';
                echo '<div class="restaurant-info">';
                echo '<h2>'.$row["name"].'</h2>';
                echo '<p class="address">'.$row["address"].'</p>';
                echo '<p class="description">'.$row["description"].'</p>';
                echo '<a href="../meniuri/index.php?id='.$row["id"].'" class="view-menu">Vezi Meniul</a>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
