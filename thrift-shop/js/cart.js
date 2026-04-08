// Shopping Cart JavaScript

let cart = [];

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('thrift_cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
    updateCartDisplay();
    updateCartCount();
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('thrift_cart', JSON.stringify(cart));
    updateCartCount();
}

// Add item to cart
function addToCart(item) {
    const existingItem = cart.find(cartItem => cartItem.id === item.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            image: item.image,
            quantity: 1
        });
    }
    
    saveCart();
    updateCartDisplay();
    showNotification(`${item.name} added to cart!`);
}

// Update cart count in navigation
function updateCartCount() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

// Update cart display
function updateCartDisplay() {
    const container = document.getElementById('cart-container');
    if (!container) return;
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="cart-container empty-cart">
                <h2>Your cart is empty</h2>
                <p>Start shopping for unique vintage finds!</p>
                <a href="shop.html" class="btn" style="margin-top: 20px;">Shop Now</a>
            </div>
        `;
        return;
    }
    
    let total = 0;
    
    const cartHtml = `
        <div class="cart-container">
            <h1 class="section-title">Shopping Cart</h1>
            ${cart.map(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                return `
                    <div class="cart-item" data-id="${item.id}">
                        <img src="images/clothing/${item.image}" alt="${item.name}" onerror="this.src='https://via.placeholder.com/80x80?text=Item'">
                        <div class="cart-item-details">
                            <h3>${escapeHtml(item.name)}</h3>
                            <p class="cart-item-price">$${item.price.toFixed(2)} each</p>
                        </div>
                        <div class="cart-item-quantity">
                            <input type="number" value="${item.quantity}" min="1" max="99" onchange="updateQuantity(${item.id}, this.value)">
                        </div>
                        <div>
                            <span class="cart-item-price">$${itemTotal.toFixed(2)}</span>
                            <button class="remove-btn" onclick="removeFromCart(${item.id})">Remove</button>
                        </div>
                    </div>
                `;
            }).join('')}
            
            <div class="cart-summary">
                <h3>Total: $${total.toFixed(2)}</h3>
                <button class="btn checkout-btn" onclick="checkout()">Proceed to Checkout</button>
            </div>
        </div>
    `;
    
    container.innerHTML = cartHtml;
}

// Update quantity
function updateQuantity(id, quantity) {
    const item = cart.find(cartItem => cartItem.id === id);
    if (item) {
        quantity = parseInt(quantity);
        if (quantity > 0) {
            item.quantity = quantity;
        } else {
            removeFromCart(id);
            return;
        }
        saveCart();
        updateCartDisplay();
    }
}

// Remove from cart
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    updateCartDisplay();
}

// Checkout
function checkout() {
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    alert('Thank you for shopping! This is a demo checkout. In production, this would process payment.');
    // Clear cart
    cart = [];
    saveCart();
    updateCartDisplay();
}

// Helper function
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #2c6e3c;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 1000;
        animation: fadeInOut 3s ease;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialize cart when page loads
document.addEventListener('DOMContentLoaded', () => {
    loadCart();
});

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(20px); }
        15% { opacity: 1; transform: translateY(0); }
        85% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(20px); }
    }
`;
document.head.appendChild(style);