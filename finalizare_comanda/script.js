document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const orderSummary = document.getElementById('orderSummary');
    const totalAmount = document.getElementById('totalAmount');
    
    // Afișăm produsele din coș
    let subtotal = 0;
    const deliveryFee = 10;
    
    orderSummary.innerHTML = '<h3>Produse comandate:</h3>';
    cart.forEach(item => {
        subtotal += item.price;
        orderSummary.innerHTML += `
            <div class="order-item">
                <span>${item.name}</span>
                <span>${item.price} RON</span>
            </div>
        `;
    });
    
    const total = subtotal + deliveryFee;
    totalAmount.textContent = `${total} RON (inclusiv livrare ${deliveryFee} RON)`;
    
    document.getElementById('checkoutForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        formData.append('total', total);
        formData.append('produse', JSON.stringify(cart));
        
        try {
            const response = await fetch('process_order.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if(data.success) {
                localStorage.removeItem('cart');
                window.location.href = 'success.php';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});
