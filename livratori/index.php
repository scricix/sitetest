<?php
session_start();
$conn = new mysqli("localhost", "root", "", "magazin");

if (!isset($_SESSION['driver_id'])) {
    header("Location: login.php");
    die();
}

// Verify if the driver exists and is active
$driver_id = $_SESSION['driver_id'];
$stmt = $conn->prepare("SELECT id FROM drivers WHERE id = ? AND status = 1");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result->fetch_assoc()) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Obținem numele livratorului
$driver_name = $_SESSION['driver_name'];
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Livratori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-top: 20px;
            position: relative;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .filter-select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .order-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .order-number {
            font-weight: bold;
            color: #333;
        }
        .status-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .status-btn {
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .status-btn.active {
            background-color: #4CAF50;
            color: #fff;
        }
        .status-btn.cancel {
            background-color: #f44336;
            color: #fff;
        }
        .order-details, .order-items {
            margin-bottom: 10px;
        }
        .order-details p, .order-items .item, .order-items .total {
            margin: 5px 0;
        }
        .order-items .item, .order-items .total {
            display: flex;
            justify-content: space-between;
        }
        .order-items .total {
            font-weight: bold;
        }
        .logout-btn {
            background-color: #f44336;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .driver-info {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 16px;
            color: #333;
        }
        .accept-btn {
            background-color: #2196F3;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .enable-sound-btn, .disable-sound-btn {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .disable-sound-btn {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Livratori</h1>
        <div class="driver-info">Bun venit, <?php echo $driver_name; ?>!</div>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Delogare</button>
        
        <button class="enable-sound-btn" onclick="enableSound()">Permite sunetul</button>
        <button class="disable-sound-btn" onclick="stopSound()" style="display: none;">Oprește sunetul</button>
        
        <div class="filters">
            <select class="filter-select" id="statusFilter">
                <option value="all">Toate comenzile</option>
                <option value="În așteptare">În așteptare</option>
                <option value="În preparare">În preparare</option>
                <option value="În livrare">În livrare</option>
                <option value="Livrată">Livrate</option>
                <option value="Anulată">Anulate</option>
            </select>
            
            <select class="filter-select" id="restaurantFilter">
                <option value="all">Toate restaurantele</option>
                <?php
                $restaurants = $conn->query("SELECT DISTINCT restaurant_name FROM comenzi");
                while($restaurant = $restaurants->fetch_assoc()) {
                    echo "<option value='".$restaurant['restaurant_name']."'>".$restaurant['restaurant_name']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="orders-grid" id="ordersGrid">
            <?php
            // Afișăm toate comenzile care nu sunt acceptate sau sunt acceptate de livratorul curent
            $orders = $conn->query("SELECT * FROM comenzi WHERE accepted_by IS NULL OR accepted_by = $driver_id ORDER BY id DESC");
            while($order = $orders->fetch_assoc()) {
                $products = json_decode($order['produse'], true);
                $restaurante = [];

                foreach($products as $product) {
                    $restaurante[$product['restaurant']][] = $product;
                }
                ?>
                <div class="order-card" 
                     data-order-id="<?php echo $order['id']; ?>"
                     data-status="<?php echo $order['status']; ?>"
                     data-restaurant="<?php echo $order['restaurant_name']; ?>">
                    <div class="order-header">
                        <span class="order-number">Comanda #<?php echo $order['id']; ?></span>
                        <span><?php echo $order['restaurant_name']; ?></span>
                    </div>
                    
                    <?php if ($order['accepted_by'] == null): ?>
                        <button class="accept-btn" onclick="acceptOrder(<?php echo $order['id']; ?>, this)">Acceptă comanda</button>
                    <?php endif; ?>
                    
                    <div class="status-buttons">
                        <button class="status-btn <?php echo $order['status'] == 'În așteptare' ? 'active' : ''; ?>" 
                                onclick="updateStatus(<?php echo $order['id']; ?>, 'În așteptare', this)"
                                <?php echo $order['status'] == 'În așteptare' ? 'disabled' : ''; ?>>
                            În așteptare
                        </button>
                        <button class="status-btn <?php echo $order['status'] == 'În preparare' ? 'active' : ''; ?>" 
                                onclick="updateStatus(<?php echo $order['id']; ?>, 'În preparare', this)"
                                <?php echo $order['status'] == 'În preparare' ? 'disabled' : ''; ?>>
                            În preparare
                        </button>
                        <button class="status-btn <?php echo $order['status'] == 'În livrare' ? 'active' : ''; ?>" 
                                onclick="updateStatus(<?php echo $order['id']; ?>, 'În livrare', this)"
                                <?php echo $order['status'] == 'În livrare' ? 'disabled' : ''; ?>>
                            În livrare
                        </button>
                        <button class="status-btn <?php echo $order['status'] == 'Livrată' ? 'active' : ''; ?>" 
                                onclick="updateStatus(<?php echo $order['id']; ?>, 'Livrată', this)"
                                <?php echo $order['status'] == 'Livrată' ? 'disabled' : ''; ?>>
                            Livrată
                        </button>
                        <button class="status-btn cancel <?php echo $order['status'] == 'Anulată' ? 'active' : ''; ?>" 
                                onclick="updateStatus(<?php echo $order['id']; ?>, 'Anulată', this)"
                                <?php echo $order['status'] == 'Anulată' ? 'disabled' : ''; ?>>
                            Anulată
                        </button>
                    </div>

                    <div class="order-details">
                        <p><strong>Client:</strong> <?php echo $order['nume_client']; ?></p>
                        <p><strong>Telefon:</strong> <?php echo $order['telefon']; ?></p>
                        <p><strong>Adresa:</strong> <?php echo $order['adresa']; ?></p>
                        <p><strong>Data comenzii:</strong> <?php echo $order['data_comanda']; ?></p>
                    </div>

                    <div class="order-items">
                        <?php
                        foreach($restaurante as $restaurant => $products) {
                            echo "<h4>Restaurant: $restaurant</h4>";
                            foreach($products as $product) {
                                echo "<div class='item'>";
                                echo "<span>{$product['name']}</span>";
                                echo "<span>{$product['price']} RON</span>";
                                echo "</div>";
                            }
                        }
                        ?>
                        <div class="total">
                            <span>Total:</span>
                            <span><?php echo $order['total']; ?> RON</span>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <audio id="notificationSound" src="notification.mp3" preload="auto"></audio>

    <script>
    let lastOrderCount = 0;
    let soundEnabled = false;

    function enableSound() {
        soundEnabled = true;
        document.querySelector('.enable-sound-btn').style.display = 'none';
        document.querySelector('.disable-sound-btn').style.display = 'block';
    }

    function stopSound() {
        const audio = document.getElementById('notificationSound');
        audio.pause();
        audio.currentTime = 0;
        document.querySelector('.disable-sound-btn').style.display = 'none';
    }

    function updateStatus(orderId, newStatus, button) {
        button.disabled = true;
        button.style.opacity = '0.5';
        button.style.cursor = 'not-allowed';
        button.classList.add('active');

        fetch('update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const card = button.closest('.order-card');
                card.dataset.status = newStatus;
                // Dezactivăm toate butoanele de status
                card.querySelectorAll('.status-btn').forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                });
            }
        });
    }

    function acceptOrder(orderId, button) {
        fetch('accept_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                button.disabled = true;
                button.innerText = 'Comanda acceptată';
                button.style.backgroundColor = '#4CAF50';
                refreshOrders();
            }
        });
    }

    function refreshOrders() {
        fetch('get_orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ordersGrid = document.getElementById('ordersGrid');
                const currentOrderCount = data.orders.length;
                if (soundEnabled && currentOrderCount > lastOrderCount) {
                    const audio = document.getElementById('notificationSound');
                    audio.play().catch(() => {});
                    document.querySelector('.disable-sound-btn').style.display = 'block';
                }
                lastOrderCount = currentOrderCount;

                ordersGrid.innerHTML = '';
                data.orders.forEach(order => {
                    const orderCard = document.createElement('div');
                    orderCard.classList.add('order-card');
                    orderCard.dataset.orderId = order.id;
                    orderCard.dataset.status = order.status;
                    orderCard.dataset.restaurant = order.restaurant_name;

                    const restaurante = {};
                    order.produse.forEach(product => {
                        if (!restaurante[product.restaurant]) {
                            restaurante[product.restaurant] = [];
                        }
                        restaurante[product.restaurant].push(product);
                    });

                    orderCard.innerHTML = `
                        <div class="order-header">
                            <span class="order-number">Comanda #${order.id}</span>
                            <span>${order.restaurant_name}</span>
                        </div>
                        ${order.accepted_by === null ? `<button class="accept-btn" onclick="acceptOrder(${order.id}, this)">Acceptă comanda</button>` : ''}
                        <div class="status-buttons">
                            <button class="status-btn ${order.status === 'În așteptare' ? 'active' : ''}" onclick="updateStatus(${order.id}, 'În așteptare', this)" ${order.status === 'În așteptare' ? 'disabled' : ''}>În așteptare</button>
                            <button class="status-btn ${order.status === 'În preparare' ? 'active' : ''}" onclick="updateStatus(${order.id}, 'În preparare', this)" ${order.status === 'În preparare' ? 'disabled' : ''}>În preparare</button>
                            <button class="status-btn ${order.status === 'În livrare' ? 'active' : ''}" onclick="updateStatus(${order.id}, 'În livrare', this)" ${order.status === 'În livrare' ? 'disabled' : ''}>În livrare</button>
                            <button class="status-btn ${order.status === 'Livrată' ? 'active' : ''}" onclick="updateStatus(${order.id}, 'Livrată', this)" ${order.status === 'Livrată' ? 'disabled' : ''}>Livrată</button>
                            <button class="status-btn cancel ${order.status === 'Anulată' ? 'active' : ''}" onclick="updateStatus(${order.id}, 'Anulată', this)" ${order.status === 'Anulată' ? 'disabled' : ''}>Anulată</button>
                        </div>
                        <div class="order-details">
                            <p><strong>Client:</strong> ${order.nume_client}</p>
                            <p><strong>Telefon:</strong> ${order.telefon}</p>
                            <p><strong>Adresa:</strong> ${order.adresa}</p>
                            <p><strong>Data comenzii:</strong> ${order.data_comanda}</p>
                        </div>
                        <div class="order-items">
                            ${Object.keys(restaurante).map(restaurant => `
                                <h4>Restaurant: ${restaurant}</h4>
                                ${restaurante[restaurant].map(product => `
                                    <div class="item">
                                        <span>${product.name}</span>
                                        <span>${product.price} RON</span>
                                    </div>
                                `).join('')}
                            `).join('')}
                            <div class="total">
                                <span>Total:</span>
                                <span>${order.total} RON</span>
                            </div>
                        </div>
                    `;
                    ordersGrid.appendChild(orderCard);
                });
            }
        });
    }

    function filterOrders() {
        const statusFilter = document.getElementById('statusFilter').value;
        const restaurantFilter = document.getElementById('restaurantFilter').value;
        
        document.querySelectorAll('.order-card').forEach(card => {
            const matchesStatus = statusFilter === 'all' || card.dataset.status === statusFilter;
            const matchesRestaurant = restaurantFilter === 'all' || card.dataset.restaurant === restaurantFilter;
            
            card.style.display = matchesStatus && matchesRestaurant ? 'block' : 'none';
        });
    }

    document.getElementById('statusFilter').addEventListener('change', filterOrders);
    document.getElementById('restaurantFilter').addEventListener('change', filterOrders);

    // Verificăm comenzile noi la fiecare 5 secunde
    setInterval(refreshOrders, 5000);
    </script>
</body>
</html>