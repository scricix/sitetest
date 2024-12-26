<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meniu Restaurant</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .restaurant-title {
            color: rgb(253, 4, 4);
            font-size: 2em;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .cart-modal {
            position: fixed;
            right: -100%;
            top: 0;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.2);
            z-index: 1000;
            padding: 20px;
            transition: right 0.3s ease-in-out;
            visibility: hidden;
        }

        .cart-modal.active {
            right: 0;
            visibility: visible;
        }

        .close-cart {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            transition: transform 0.2s;
        }

        .close-cart:hover {
            transform: scale(1.1);
            color: #ff0000;
        }

        .cart-modal-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .restaurant-display {
            color: rgb(253, 4, 4);
            font-weight: bold;
            margin: 2px 0;
        }

        .item-name {
            font-weight: bold;
        }

        .item-price {
            color: #2196F3;
        }

        .add-to-cart {
            background: linear-gradient(45deg, #2196F3, #3f51b5, #9c27b0);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1em;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
            position: relative;
            overflow: hidden;
            width: 80%;
            margin: 15px auto;
            display: block;
        }

        .add-to-cart:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
            background: linear-gradient(45deg, #1e88e5, #3949ab, #8e24aa);
        }

        .add-to-cart:active {
            transform: scale(0.95);
        }

        .add-to-cart[disabled] {
            background: linear-gradient(45deg, #9e9e9e, #757575);
            cursor: not-allowed;
            opacity: 0.7;
            box-shadow: none;
        }

        .add-to-cart::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255,255,255,.8);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .add-to-cart.clicked::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.8;
            }
            100% {
                transform: scale(50, 50);
                opacity: 0;
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(33, 150, 243, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(33, 150, 243, 0);
            }
        }

        .add-to-cart:not([disabled]):hover {
            animation: pulse 1.5s infinite;
        }

        .cart-widget {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1001;
            transition: transform 0.3s;
        }

        .cart-widget:hover {
            transform: scale(1.1);
        }

        .cart-count {
            background: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -5px;
            right: -5px;
        }
    </style>
</head>
<body>
    <div class="cart-widget" onclick="toggleCart()">
        🛒 <span class="cart-count">0</span>
    </div>

    <div class="cart-modal" id="cartModal">
        <button class="close-cart" onclick="toggleCart()">×</button>
        <h3>Coșul tău</h3>
        <div class="cart-modal-items"></div>
    </div>

    <div class="container">
        <?php
        $conexiune = new mysqli("localhost", "root", "", "magazin");
        $nume_restaurant = '';

        if(isset($_GET['id'])) {
            $id_restaurant = $_GET['id'];
            
            $sql_restaurant = "SELECT name FROM restaurants WHERE id = ?";
            $stmt_restaurant = $conexiune->prepare($sql_restaurant);
            $stmt_restaurant->bind_param("i", $id_restaurant);
            $stmt_restaurant->execute();
            $result_restaurant = $stmt_restaurant->get_result();
            $restaurant_data = $result_restaurant->fetch_assoc();
            $nume_restaurant = $restaurant_data['name'];
        ?>
        
        <a href="../restaurante/index.php" class="back-arrow">
            <svg viewBox="0 0 24 24" width="24" height="24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
        </a>

        <h1 class="restaurant-title">Meniu <?php echo $nume_restaurant; ?></h1>

        <div class="menu-categories">
            <button class="category-btn active" data-category="all">Toate</button>
            <button class="category-btn" data-category="Pizza">Pizza</button>
            <button class="category-btn" data-category="Paste">Paste</button>
            <button class="category-btn" data-category="Salate">Salate</button>
            <button class="category-btn" data-category="Bauturi">Băuturi</button>
        </div>

        <div class="menu-grid">
            <?php
                $sql = "SELECT * FROM products WHERE restaurant_id = ? ORDER BY category";
                $stmt = $conexiune->prepare($sql);
                $stmt->bind_param("i", $id_restaurant);
                $stmt->execute();
                $rezultat = $stmt->get_result();
                
                while($produs = $rezultat->fetch_assoc()) {
                    echo '<div class="menu-item" data-category="'.$produs["category"].'">';
                    echo '<img src="../cms/meniuri/'.$produs["image_path"].'" alt="'.$produs["name"].'">';
                    echo '<div class="menu-item-info">';
                    echo '<h3>'.$produs["name"].'</h3>';
                    echo '<p class="ingredients">'.$produs["ingredients"].'</p>';
                    
                    if($produs["is_available"] == 0) {
                        echo '<p class="unavailable-message">Produs indisponibil momentan</p>';
                        echo '<button class="add-to-cart" disabled>Indisponibil</button>';
                    } else {
                        if($produs["special_offer"] == 1) {
                            echo '<p class="offer-price">'.$produs["offer_price"].' RON</p>';
                            echo '<p class="original-price"><s>'.$produs["price"].' RON</s></p>';
                            echo '<button class="add-to-cart" data-id="'.$produs["id"].'" data-price="'.$produs["offer_price"].'" data-restaurant="'.$nume_restaurant.'">Adaugă în coș</button>';
                        } else {
                            echo '<p class="price">'.$produs["price"].' RON</p>';
                            echo '<button class="add-to-cart" data-id="'.$produs["id"].'" data-price="'.$produs["price"].'" data-restaurant="'.$nume_restaurant.'">Adaugă în coș</button>';
                        }
                    }
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>

    <script>
    let cos = JSON.parse(localStorage.getItem('cart')) || [];

    function toggleCart() {
        const modal = document.getElementById('cartModal');
        modal.classList.toggle('active');
        if(modal.classList.contains('active')) {
            actualizeazaModalCos();
        }
    }

    document.querySelectorAll('.add-to-cart:not([disabled])').forEach(buton => {
        buton.addEventListener('click', () => {
            const idProdus = buton.dataset.id;
            const pret = parseFloat(buton.dataset.price);
            const nume = buton.parentElement.querySelector('h3').textContent;
            const restaurant = buton.dataset.restaurant;
            
            buton.classList.add('clicked');
            setTimeout(() => buton.classList.remove('clicked'), 700);
            
            cos.push({
                id: idProdus,
                name: nume,
                price: pret,
                restaurant: restaurant
            });
            
            localStorage.setItem('cart', JSON.stringify(cos));
            actualizeazaAfisareCos();
            
            buton.innerHTML = '✓ Adăugat';
            buton.style.background = 'linear-gradient(45deg, #4CAF50, #45a049)';
            setTimeout(() => {
                buton.innerHTML = 'Adaugă în coș';
                buton.style.background = 'linear-gradient(45deg, #2196F3, #3f51b5, #9c27b0)';
            }, 1000);
        });
    });

    function actualizeazaAfisareCos() {
        document.querySelector('.cart-count').textContent = cos.length;
        actualizeazaModalCos();
    }

    function actualizeazaModalCos() {
        const elementeCos = document.querySelector('.cart-modal-items');
        elementeCos.innerHTML = '';
        
        const taxaLivrare = 10;
        let subtotal = 0;
        let restaurante = {};

        if (cos.length > 0) {
            cos.forEach((produs, index) => {
                subtotal += produs.price;
                if (!restaurante[produs.restaurant]) {
                    restaurante[produs.restaurant] = [];
                }
                restaurante[produs.restaurant].push({ ...produs, index });
            });

            for (let restaurant in restaurante) {
                elementeCos.innerHTML += `<h4>${restaurant}</h4>`;
                restaurante[restaurant].forEach(produs => {
                    elementeCos.innerHTML += `
                        <div class="cart-modal-item">
                            <div>
                                <div class="item-name">${produs.name}</div>
                                <div class="item-price">${produs.price} RON</div>
                            </div>
                            <button onclick="stergeDinCos(${produs.index})" title="Șterge produsul">×</button>
                        </div>
                    `;
                });
            }

            elementeCos.innerHTML += `
                <div class="cart-summary">
                    <div class="delivery-fee">
                        <span>Transport:</span>
                        <span>${taxaLivrare} RON</span>
                    </div>
                    <div class="total-price">
                        <span>Total:</span>
                        <span>${(subtotal + taxaLivrare).toFixed(2)} RON</span>
                    </div>
                    <button class="checkout-btn" onclick="finalizeazaComanda()">Finalizează comanda</button>
                </div>
            `;
        } else {
            elementeCos.innerHTML = `
                <div style="padding: 20px; text-align: center; color: #666;">
                    Coșul tău este gol
                </div>
            `;
        }
    }

    function stergeDinCos(index) {
        const element = document.querySelectorAll('.cart-modal-item')[index];
        element.classList.add('removing');
        
        setTimeout(() => {
            cos.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cos));
            actualizeazaAfisareCos();
        }, 300);
    }

    function finalizeazaComanda() {
        const butonFinalizare = document.querySelector('.checkout-btn');
        butonFinalizare.innerHTML = '<span style="display: inline-block; animation: pulse 1s infinite">Se procesează...</span>';
        butonFinalizare.style.background = 'linear-gradient(45deg, #3f51b5, #2196F3)';
        
        setTimeout(() => {
            window.location.href = '../finalizare_comanda/index.php';
        }, 800);
    }

    document.addEventListener('click', (e) => {
        const modal = document.getElementById('cartModal');
        const widgetCos = document.querySelector('.cart-widget');
        if (!modal.contains(e.target) && !widgetCos.contains(e.target) && modal.classList.contains('active')) {
            toggleCart();
        }
    });

    actualizeazaAfisareCos();
    </script>
</body>
</html>