<?php
$conn = new mysqli("localhost", "root", "", "magazin");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Produs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Editare Produs</h2>
        <form action="update_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            
            <div class="form-group">
                <label>Selectează Restaurant:</label>
                <select name="restaurant_id" required>
                    <?php
                    $sql = "SELECT id, name FROM restaurants";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        $selected = ($row['id'] == $product['restaurant_id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' ".$selected.">".$row['name']."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Categorie:</label>
                <select name="category" required>
                    <?php
                    $categories = ['Pizza', 'Paste', 'Salate', 'Deserturi', 'Băuturi', 'Preparate la grătar', 'Supe/Ciorbe', 'Garnituri'];
                    foreach($categories as $cat) {
                        $selected = ($cat == $product['category']) ? 'selected' : '';
                        echo "<option value='$cat' $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nume Produs:</label>
                <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Ingrediente:</label>
                <textarea name="ingredients" required><?php echo $product['ingredients']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Preț:</label>
                <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Super Ofertă:</label>
                <input type="checkbox" name="special_offer" id="special_offer" <?php echo $product['special_offer'] ? 'checked' : ''; ?>>
            </div>

            <div class="form-group" id="offer_price_group" style="display: <?php echo $product['special_offer'] ? 'block' : 'none'; ?>">
                <label>Preț Ofertă:</label>
                <input type="number" step="0.01" name="offer_price" value="<?php echo $product['offer_price']; ?>">
            </div>
            
            <div class="form-group">
                <label>Imagine Curentă:</label>
                <img src="<?php echo $product['image_path']; ?>" style="max-width: 200px;">
                <label>Schimbă Imaginea (opțional):</label>
                <input type="file" name="image" accept="image/*">
            </div>
            
            <button type="submit">Salvează Modificările</button>
        </form>
    </div>

    <script>
    document.getElementById('special_offer').addEventListener('change', function() {
        document.getElementById('offer_price_group').style.display = this.checked ? 'block' : 'none';
    });
    </script>
</body>
</html>
