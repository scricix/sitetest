<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizare Comandă</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(27, 5, 233, 0.1);
        }

        .customer-details {
            margin-bottom: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .order-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .restaurant-display {
            color: rgb(245, 73, 5);
            font-weight: bold;
            margin: 2px 0;
            font-size: 0.9em;
        }

        .total {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            color: #2196F3;
            text-align: right;
            padding: 10px 0;
        }

        .place-order {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-size: 1.1em;
            transition: all 0.3s ease;
        }

        .place-order:hover {
            background: linear-gradient(45deg, #45a049, #4CAF50);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(76, 175, 80, 0.2);
        }

        .place-order:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .error-message {
            color: #ff0000;
            font-size: 0.9em;
            margin-top: 5px;
            display: none;
        }

        .back-arrow {
            display: inline-block;
            margin-bottom: 20px;
            color: #333;
            text-decoration: none;
        }

        .back-arrow:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../meniuri/index.php" class="back-arrow">
            ← Înapoi la meniu
        </a>
        
        <h2>Finalizare Comandă</h2>
        
        <form id="customerForm" class="customer-details" method="POST" action="salveaza_comanda.php">
            <div class="form-group">
                <label for="nume">Nume:</label>
                <input type="text" id="nume" name="nume" required>
                <div class="error-message" id="numeError">Vă rugăm introduceți numele</div>
            </div>
            
            <div class="form-group">
                <label for="prenume">Prenume:</label>
                <input type="text" id="prenume" name="prenume" required>
                <div class="error-message" id="prenumeError">Vă rugăm introduceți prenumele</div>
            </div>
            
            <div class="form-group">
                <label for="telefon">Număr de telefon:</label>
                <input type="tel" id="telefon" name="telefon" pattern="[0-9]{10}" required>
                <div class="error-message" id="telefonError">Vă rugăm introduceți un număr de telefon valid (10 cifre)</div>
            </div>
            
            <div class="form-group">
                <label for="adresa">Adresa de livrare:</label>
                <textarea id="adresa" name="adresa" required></textarea>
                <div class="error-message" id="adresaError">Vă rugăm introduceți adresa de livrare</div>
            </div>

            <input type="hidden" name="total" value="">
            <input type="hidden" name="produse" value="">
            <input type="hidden" name="restaurant_id" value="">
            
            <button type="submit" class="place-order">Plasează comanda</button>
        </form>

        <div id="orderDetails"></div>
    </div>

    <script>
    const cos = JSON.parse(localStorage.getItem('cart')) || [];
    const containerCos = document.getElementById('orderDetails');
    const taxaLivrare = 10;
    let subtotal = 0;

    if (cos.length > 0) {
        let restauranteGrupate = {};
        
        cos.forEach(produs => {
            if (!restauranteGrupate[produs.restaurant]) {
                restauranteGrupate[produs.restaurant] = [];
            }
            restauranteGrupate[produs.restaurant].push(produs);
            subtotal += produs.price;
        });

        for (let restaurant in restauranteGrupate) {
            restauranteGrupate[restaurant].forEach(produs => {
                containerCos.innerHTML += `
                    <div class="order-item">
                        <div>
                            <div>${produs.name}</div>
                            <div class="restaurant-display">Restaurant: ${produs.restaurant}</div>
                        </div>
                        <div>${produs.price} RON</div>
                    </div>
                `;
            });
        }

        containerCos.innerHTML += `
            <div class="order-item">
                <div>Transport</div>
                <div>${taxaLivrare} RON</div>
            </div>
            <div class="total">
                Total comandă: ${(subtotal + taxaLivrare).toFixed(2)} RON
            </div>
        `;
    } else {
        containerCos.innerHTML = '<p>Nu există produse în coș.</p>';
    }
    document.getElementById('customerForm').onsubmit = function(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return false;
    }

    const firstProduct = cos[0];
    const restaurantName = firstProduct ? firstProduct.restaurant : '';

    this.querySelector('[name="total"]').value = subtotal + taxaLivrare;
    this.querySelector('[name="produse"]').value = JSON.stringify(cos);
    this.querySelector('[name="restaurant_id"]').value = restaurantName;
    
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<span style="display: inline-block; animation: pulse 1s infinite">Se procesează...</span>';
    
    localStorage.removeItem('cart');
    this.submit();
};


    function validateForm() {
        let isValid = true;
        const fields = ['nume', 'prenume', 'telefon', 'adresa'];
        
        fields.forEach(field => {
            const element = document.getElementById(field);
            const errorElement = document.getElementById(`${field}Error`);
            
            if (!element.value.trim()) {
                errorElement.style.display = 'block';
                isValid = false;
            } else {
                errorElement.style.display = 'none';
            }
            
            if (field === 'telefon' && !element.value.match(/^[0-9]{10}$/)) {
                errorElement.style.display = 'block';
                isValid = false;
            }
        });
        
        return isValid;
    }
    </script>
</body>
</html>
