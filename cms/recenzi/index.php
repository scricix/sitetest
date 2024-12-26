<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Meniuri</title>
    <link rel="stylesheet" href="style.css">
</head>

<a href="../index.html" class="back-arrow">
    <svg viewBox="0 0 24 24" width="24" height="24">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
    </svg>
</a>

<body>
    <div class="container">
        <div class="menu-grid">
            <?php
            $conn = new mysqli("localhost", "root", "", "magazin");
            
            $sql = "SELECT p.*, pr.rating as current_rating
                    FROM products p 
                    LEFT JOIN product_ratings pr ON p.id = pr.product_id 
                    ORDER BY p.category";
            
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                echo '<div class="menu-card">';
                echo '<img src="../'.$row["image_path"].'" alt="'.$row["name"].'">';
                echo '<h3>'.$row["name"].'</h3>';
                echo '<p class="category">'.$row["category"].'</p>';
                echo '<p class="price">'.$row["price"].' RON</p>';
                
                echo '<form action="save_rating.php" method="POST" class="rating-form">';
                echo '<input type="hidden" name="product_id" value="'.$row["id"].'">';
                echo '<div class="star-rating">';
                for($i = 5; $i >= 1; $i--) {
                    $checked = ($row['current_rating'] == $i) ? 'checked' : '';
                    echo '<input type="radio" name="rating" value="'.$i.'" id="star'.$row["id"].'_'.$i.'" '.$checked.'>';
                    echo '<label for="star'.$row["id"].'_'.$i.'">★</label>';
                }
                echo '</div>';
                echo '<button type="submit" class="edit-rating">Salvează Rating</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
