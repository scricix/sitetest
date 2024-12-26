<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaugă Recenzie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        $conn = new mysqli("localhost", "root", "", "magazin");
        
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT p.*, r.name as restaurant_name 
                    FROM products p 
                    JOIN restaurants r ON p.restaurant_id = r.id 
                    WHERE p.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            echo '<div class="product-info">';
            echo '<img src="../'.$product["image_path"].'" alt="'.$product["name"].'">';
            echo '<h2>'.$product["name"].'</h2>';
            echo '<p><strong>Restaurant:</strong> '.$product["restaurant_name"].'</p>';
            echo '</div>';
        }
        ?>

        <div class="review-form-container">
            <h3>Adaugă o Recenzie</h3>
            <form action="save_review.php" method="POST" class="review-form">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                
                <div class="form-group">
                    <label>Numele tău:</label>
                    <input type="text" name="reviewer_name" required>
                </div>
                
                <div class="form-group">
                    <label>Rating:</label>
                    <div class="star-rating">
                        <input type="radio" name="rating" value="5" id="star5" required><label for="star5">★</label>
                        <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                        <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                        <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                        <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Recenzia ta:</label>
                    <textarea name="review" required></textarea>
                </div>
                
                <button type="submit">Publică Recenzia</button>
            </form>
        </div>

        <div class="existing-reviews">
            <h3>Recenzii Existente</h3>
            <?php
            $sql = "SELECT * FROM product_reviews WHERE product_id = ? ORDER BY date_added DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $reviews = $stmt->get_result();
            
            while($review = $reviews->fetch_assoc()) {
                echo '<div class="review-card">';
                echo '<div class="review-header">';
                echo '<span class="reviewer-name">'.$review['reviewer_name'].'</span>';
                echo '<span class="review-date">'.date('d.m.Y', strtotime($review['date_added'])).'</span>';
                echo '</div>';
                echo '<div class="rating">';
                for($i = 1; $i <= 5; $i++) {
                    echo $i <= $review['rating'] ? '★' : '☆';
                }
                echo '</div>';
                echo '<p class="review-text">'.$review['review'].'</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
