<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magazin";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM restaurants";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="restaurant-card">';
        echo '<img src="' . $row["image_path"] . '" alt="' . $row["name"] . '">';
        echo '<h3>' . $row["name"] . '</h3>';
        echo '<p>' . $row["description"] . '</p>';
        echo '<p>Address: ' . $row["address"] . '</p>';
        echo '<p>Phone: ' . $row["phone"] . '</p>';
        echo '<p>Hours: ' . $row["opening_time"] . ' - ' . $row["closing_time"] . '</p>';
        echo '<div class="actions">';
        echo '<a href="edit_restaurant.php?id=' . $row["id"] . '" class="btn edit">Edit</a>';
        echo '<a href="delete_restaurant.php?id=' . $row["id"] . '" class="btn delete">Delete</a>';
        echo '<form method="POST" action="toggle_availability.php">';
        echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
        echo '<button type="submit" class="btn ' . ($row["is_available"] ? 'available' : 'unavailable') . '">';
        echo $row["is_available"] ? 'Available' : 'Unavailable';
        echo '</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<p>No restaurants found</p>";
}

$conn->close();
?>
