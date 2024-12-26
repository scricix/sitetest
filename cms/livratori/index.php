<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Livratori</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <a href="../index.html" class="back-arrow">
            <svg viewBox="0 0 24 24" width="24" height="24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
        </a>

        <h2>Adaugă Livrator Nou</h2>
        <form id="driver-form" action="save_driver.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nume și Prenume:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Telefon:</label>
                <input type="tel" name="phone" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Zona de Livrare:</label>
                <select name="delivery_zone" required>
                    <option value="">Selectează zona</option>
                    <option value="Zona 1">Zona 1</option>
                    <option value="Zona 2">Zona 2</option>
                    <option value="Zona 3">Zona 3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Vehicul:</label>
                <select name="vehicle" required>
                    <option value="">Selectează vehiculul</option>
                    <option value="Mașină">Mașină</option>
                    <option value="Scuter">Scuter</option>
                    <option value="Bicicletă">Bicicletă</option>
                </select>
            </div>

            <div class="form-group">
                <label>Poză Profil:</label>
                <input type="file" name="profile_image" accept="image/*" required>
            </div>

            <button type="submit">Adaugă Livrator</button>
        </form>

        <div class="drivers-list">
            <h2>Lista Livratori</h2>
            <?php
            $conn = new mysqli("localhost", "root", "", "magazin");
            $sql = "SELECT * FROM drivers ORDER BY name";
            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
                echo '<div class="driver-card">';
                echo '<img src="'.$row["profile_image"].'" alt="'.$row["name"].'">';
                echo '<h3>'.$row["name"].'</h3>';
                echo '<p><strong>Telefon:</strong> '.$row["phone"].'</p>';
                echo '<p><strong>Email:</strong> '.$row["email"].'</p>';
                echo '<p><strong>Zona:</strong> '.$row["delivery_zone"].'</p>';
                echo '<p><strong>Vehicul:</strong> '.$row["vehicle"].'</p>';
                
                echo '<div class="action-buttons">';
                echo '<a href="edit_driver.php?id='.$row["id"].'" class="btn edit">Editează</a>';
                echo '<a href="delete_driver.php?id='.$row["id"].'" class="btn delete">Șterge</a>';
                
                echo '<form method="POST" action="toggle_status.php">';
                echo '<input type="hidden" name="id" value="'.$row["id"].'">';
                echo '<button type="submit" class="btn '.($row["status"] ? 'active' : 'inactive').'">';
                echo $row["status"] ? 'Activ' : 'Inactiv';
                echo '</button>';
                echo '</form>';
                echo '</div>';
                
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
    document.getElementById('driver-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const xhr = new XMLHttpRequest();
        
        xhr.open('POST', 'save_driver.php', true);
        xhr.onload = function() {
            location.reload();
        };
        xhr.send(formData);
    });
    </script>
</body>
</html>
