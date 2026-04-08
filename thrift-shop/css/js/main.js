
// Inventory data - just for display
const inventory = [
    { id: 1, name: "Levi's Vintage Denim Jacket", category: "mens", description: "Authentic 90s wash, perfectly broken-in, classic trucker style with original buttons. Size: Large", img: "mens/jacket1.jpg", fallback: "https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=400" },
    { id: 2, name: "Vintage Floral Maxi Dress", category: "womens", description: "Beautiful 1980s floral print dress with flowing silhouette. Perfect for summer days. Size: Medium", img: "womens/dress1.jpg", fallback: "https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=400" },
    { id: 3, name: "Corduroy Button-Up Shirt", category: "mens", description: "Rich earth tones, soft wide-wale corduroy fabric with pearl snap buttons. Size: XL", img: "mens/shirt1.jpg", fallback: "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400" },
    { id: 4, name: "Vintage Leather Satchel", category: "accessories", description: "Genuine leather bag with brass hardware. Spacious interior, perfect everyday bag.", img: "accessories/bag1.jpg", fallback: "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400" },
    { id: 5, name: "90s Neon Windbreaker", category: "vintage", description: "Retro neon colors, lightweight nylon, adjustable hood. Iconic 90s streetwear piece. Size: Medium", img: "vintage/windbreaker1.jpg", fallback: "https://images.unsplash.com/photo-1556906781-9a412961c28c?w=400" },
    { id: 6, name: "High-Waisted Mom Jeans", category: "womens", description: "Classic 90s fit, light wash denim with slight stretch. Excellent condition. Size: 28x30", img: "womens/jeans1.jpg", fallback: "https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=400" },
    { id: 7, name: "Khaki Pants for Men", category: "mens", description: "Classic khaki chino pants, comfortable fit, perfect for casual or office wear. Size: 34x32", img: "mens/khaki.jpg", fallback: "https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400" },
    { id: 8, name: "70s Suede Fringe Jacket", category: "vintage", description: "Genuine suede jacket with fringe details. True bohemian style from the 1970s. Size: Medium", img: "vintage/jacket1.jpg", fallback: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=400" },
    { id: 9, name: "Vintage Silk Blouse", category: "womens", description: "Elegant cream silk blouse with pearl buttons and puff sleeves. Timeless workwear piece. Size: Small", img: "womens/blouse1.jpg", fallback: "https://images.unsplash.com/photo-1598554747436-c9293d6a588f?w=400" },
    { id: 10, name: "Wool Trench Coat", category: "outerwear", description: "Classic camel color wool blend coat. Belted waist, sophisticated outerwear. Size: Large", img: "outerwear/coat1.jpg", fallback: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=400" },
    { id: 11, name: "Leather Bomber Jacket", category: "outerwear", description: "Genuine leather bomber jacket. Ribbed cuffs and hem, zip front. Size: Medium", img: "outerwear/bomber1.jpg", fallback: "https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400" },
    { id: 12, name: "Wool Fedora Hat", category: "accessories", description: "Classic fedora in rich brown wool. Adjustable inner band. One size fits most.", img: "accessories/hat1.jpg", fallback: "https://images.unsplash.com/photo-1521369909029-2afed882baee?w=400" },
    { id: 13, name: "Vintage Silk Scarf", category: "accessories", description: "90x90cm silk scarf with hand-rolled edges. Painterly floral pattern in jewel tones.", img: "accessories/scarf1.jpg", fallback: "https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=400" },
    { id: 14, name: "1950s Inspired Dress", category: "vintage", description: "Vintage-inspired silhouette with polka dot pattern. Fitted waist and full skirt. Size: Small", img: "vintage/dress1.jpg", fallback: "https://images.unsplash.com/photo-1539008835657-9e8e9680c956?w=400" },
    { id: 15, name: "Vintage Puffer Vest", category: "outerwear", description: "Retro 90s puffer vest in navy blue. Lightweight insulation, perfect for layering. Size: Large", img: "outerwear/puffer1.jpg", fallback: "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400" }
];

// Load shop items - display only, no buttons
function loadShopItems(filter = "all") {
    const grid = document.getElementById("shop-grid");
    if (!grid) return;

    let filteredItems = inventory;
    if (filter !== "all") {
        filteredItems = inventory.filter(item => item.category === filter);
    }

    if (filteredItems.length === 0) {
        grid.innerHTML = "<p style='text-align: center; grid-column: 1/-1; padding: 40px;'>No items found in this category. Check back soon for new arrivals.</p>";
        return;
    }

    grid.innerHTML = filteredItems.map(item => `
        <div class="featured-item">
            <img src="images/${item.img}" alt="${item.name}" onerror="this.src='${item.fallback}'">
            <h3>${escapeHtml(item.name)}</h3>
            <p>${escapeHtml(item.description)}</p>
        </div>
    `).join("");
}

// Filter items by category
function filterItems(category) {
    loadShopItems(category);
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Contact form handling
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("shop-grid")) {
        loadShopItems("all");
    }
    
    const contactForm = document.getElementById("contact-form");
    if (contactForm) {
        contactForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(contactForm);
            const messageDiv = document.getElementById("contact-message");
            
            try {
                const response = await fetch("backend/send-contact.php", {
                    method: "POST",
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    messageDiv.innerHTML = "<p style='color: #2c6e3c; background: #e0f2e6; padding: 12px; border-radius: 8px;'>✓ Message sent successfully! We'll get back to you soon.</p>";
                    contactForm.reset();
                } else {
                    messageDiv.innerHTML = "<p style='color: #721c24; background: #f8d7da; padding: 12px; border-radius: 8px;'>✗ Error sending message. Please try again.</p>";
                }
            } catch (error) {
                messageDiv.innerHTML = "<p style='color: #721c24; background: #f8d7da; padding: 12px; border-radius: 8px;'>✗ Connection error. Please try again.</p>";
            }
            
            setTimeout(() => {
                messageDiv.innerHTML = "";
            }, 5000);
        });
    }
});