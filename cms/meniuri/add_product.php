<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaugă Produs în Meniu</title>
    <link rel="stylesheet" href="style.css">
</head>

<a href="../index.html" class="back-arrow">
    <svg viewBox="0 0 24 24" width="24" height="24">
        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
    </svg>
</a>

<body>
    <div class="container">
        <h2>Adaugă Produs Nou în Meniu</h2>
        <form action="save_product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Selectează Restaurant:</label>
                <select name="restaurant_id" required>
                    <option value="">Alege restaurantul</option>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "magazin");
                    $sql = "SELECT id, name FROM restaurants";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['name']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Categorie:</label>
                <select name="category" required>
                    <option value="">Alege categoria</option>
                    <option value="Pizza">Pizza</option>
                    <option value="Paste">Paste</option>
                    <option value="Salate">Salate</option>
                    <option value="Deserturi">Deserturi</option>
                    <option value="Băuturi">Băuturi</option>
                    <option value="Preparate la grătar">Preparate la grătar</option>
                    <option value="Supe/Ciorbe">Supe/Ciorbe</option>
                    <option value="Garnituri">Garnituri</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nume Produs:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Ingrediente:</label>
                <textarea name="ingredients" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Preț:</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            
            <div class="form-group">
                <label>Super Ofertă:</label>
                <input type="checkbox" name="special_offer" id="special_offer">
            </div>

            <div class="form-group" id="offer_price_group" style="display: none;">
                <label>Preț Ofertă:</label>
                <input type="number" step="0.01" name="offer_price">
            </div>
            
            <div class="form-group">
                <label>Imagine:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            
            <button type="submit">Salvează Produsul</button>

           

        </form>

        <div class="products-list">
            <h2>Lista Produse</h2>
            <?php
            $sql = "SELECT p.*, r.name as restaurant_name 
                    FROM products p 
                    JOIN restaurants r ON p.restaurant_id = r.id 
                    ORDER BY r.name, p.category, p.name";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="'.$row["image_path"].'" alt="'.$row["name"].'">';
                echo '<h3>'.$row["name"].'</h3>';
                echo '<p><strong>Restaurant:</strong> '.$row["restaurant_name"].'</p>';
                echo '<p><strong>Categorie:</strong> '.$row["category"].'</p>';
                echo '<p><strong>Ingrediente:</strong> '.$row["ingredients"].'</p>';
                echo '<p><strong>Preț:</strong> '.$row["price"].' RON</p>';
                
                if($row["special_offer"]) {
                    echo '<div class="offer-info">';
                    echo '<span class="offer-badge">Super Ofertă!</span>';
                    echo '<p class="offer-price"><strong>Preț Ofertă:</strong> '.$row["offer_price"].' RON</p>';
                    echo '<p class="original-price"><s>Preț Original: '.$row["price"].' RON</s></p>';
                    echo '</div>';
                }
                
                echo '<div class="action-buttons">';
                echo '<a href="edit_product.php?id='.$row["id"].'" class="btn edit">Editează</a>';
                echo '<a href="delete_product.php?id='.$row["id"].'" class="btn delete">Șterge</a>';
                
                echo '<form method="POST" action="toggle_product.php" style="display: inline;">';
                echo '<input type="hidden" name="id" value="'.$row["id"].'">';
                echo '<button type="submit" class="btn '.($row["is_available"] ? 'available' : 'unavailable').'">';
                echo $row["is_available"] ? 'Disponibil' : 'Indisponibil';
                echo '</button>';
                echo '</form>';
                
                echo '<form method="POST" action="toggle_offer.php" style="display: inline;">';
                echo '<input type="hidden" name="id" value="'.$row["id"].'">';
                echo '<button type="submit" class="btn '.($row["special_offer"] ? 'offer-active' : 'offer-inactive').'">';
                echo $row["special_offer"] ? 'Dezactivează Oferta' : 'Activează Oferta';
                echo '</button>';
                echo '</form>';
                
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
    document.getElementById('special_offer').addEventListener('change', function() {
        document.getElementById('offer_price_group').style.display = this.checked ? 'block' : 'none';
    });
    </script>
</body>
</html>
