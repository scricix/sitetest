<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmare Comandă</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .thank-you {
            text-align: center;
            margin-bottom: 30px;
        }

        .thank-you h1 {
            color: #4CAF50;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .order-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .status-tracker {
            display: flex;
            justify-content: space-between;
            margin: 40px 0;
            position: relative;
        }

        .status-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .status-step .circle {
            width: 30px;
            height: 30px;
            background: #ddd;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .status-step.active .circle {
            background: #4CAF50;
            color: white;
        }

        .status-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #ddd;
            z-index: 0;
        }

        .progress-line {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: #4CAF50;
            transition: width 0.5s ease;
        }

        .order-items {
            margin-top: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-total {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .order-total > div {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }

        .total {
            border-top: 2px solid #eee;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 1.2em;
            color: #2196F3;
        }

        .home-button {
            display: inline-block;
            padding: 12px 24px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s ease;
        }

        .home-button:hover {
            background: #45a049;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="container fade-in">
        <?php
        $conn = new mysqli("localhost", "root", "", "magazin");
        $last_order = $conn->query("SELECT * FROM comenzi ORDER BY id DESC LIMIT 1")->fetch_assoc();
        ?>

        <div class="thank-you">
            <h1>Mulțumim pentru comandă!</h1>
            <p>Comanda ta a fost înregistrată cu succes și va fi procesată în curând.</p>
        </div>

        <div class="order-details">
            <h3>Detalii comandă:</h3>
            <p><strong>Număr comandă:</strong> #<?php echo $last_order['id']; ?></p>
            <p><strong>Restaurant:</strong> <?php echo $last_order['restaurant_name']; ?></p>
            <p><strong>Client:</strong> <?php echo $last_order['nume_client']; ?></p>
            <p><strong>Telefon:</strong> <?php echo $last_order['telefon']; ?></p>
            <p><strong>Adresa:</strong> <?php echo $last_order['adresa']; ?></p>
        </div>

        <div class="status-tracker">
            <div class="status-line">
                <div class="progress-line" id="progressLine"></div>
            </div>
            <div class="status-step active">
                <div class="circle">1</div>
                <div>Comandă plasată</div>
            </div>
            <div class="status-step">
                <div class="circle">2</div>
                <div>În preparare</div>
            </div>
            <div class="status-step">
                <div class="circle">3</div>
                <div>În livrare</div>
            </div>
            <div class="status-step">
                <div class="circle">4</div>
                <div>Livrată</div>
            </div>
            <div class="status-step">
                <div class="circle">X</div>
                <div>Anulată</div>
            </div>
        </div>

        <div class="order-items">
            <h3>Produse comandate:</h3>
            <?php
            $produse = json_decode($last_order['produse'], true);
            $restaurante = [];

            foreach($produse as $produs) {
                $restaurante[$produs['restaurant']][] = $produs;
            }

            foreach($restaurante as $restaurant => $produse) {
                echo "<h4>Restaurant: $restaurant</h4>";
                foreach($produse as $produs) {
                    echo "<div class='order-item'>";
                    echo "<div>{$produs['name']}</div>";
                    echo "<div>{$produs['price']} RON</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="order-total">
            <div class="subtotal">
                <span>Subtotal:</span>
                <span><?php echo $last_order['total'] - 10; ?> RON</span>
            </div>
            <div class="delivery-fee">
                <span>Transport:</span>
                <span>10 RON</span>
            </div>
            <div class="total">
                <span>Total:</span>
                <span><?php echo $last_order['total']; ?> RON</span>
            </div>
        </div>

        <a href="../restaurante/index.php" class="home-button">Înapoi la restaurante</a>
    </div>

    <script>
    function updateStatus() {
        fetch('get_order_status.php?order_id=<?php echo $last_order['id']; ?>')
            .then(response => response.json())
            .then(data => {
                const statuses = ['În așteptare', 'În preparare', 'În livrare', 'Livrată', 'Anulată'];
                const currentIndex = statuses.indexOf(data.status);
                const steps = document.querySelectorAll('.status-step');
                const progressLine = document.getElementById('progressLine');
                
                if(data.status === 'Anulată') {
                    steps.forEach(step => step.classList.remove('active'));
                    steps[4].classList.add('active');
                    steps[4].querySelector('.circle').style.background = '#ff4444';
                    progressLine.style.background = '#ff4444';
                    progressLine.style.width = '100%';
                } else {
                    steps.forEach((step, index) => {
                        if(index <= currentIndex) {
                            step.classList.add('active');
                            step.querySelector('.circle').style.background = '#4CAF50';
                        } else {
                            step.classList.remove('active');
                            step.querySelector('.circle').style.background = '#ddd';
                        }
                    });
                    progressLine.style.width = `${(currentIndex / (statuses.length - 2)) * 100}%`;
                    progressLine.style.background = '#4CAF50';
                }
            });
    }

    updateStatus();
    setInterval(updateStatus, 10000);
    </script>
</body>
</html>