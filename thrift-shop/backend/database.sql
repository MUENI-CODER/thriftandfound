-- Create database
CREATE DATABASE IF NOT EXISTS thrift_shop;
USE thrift_shop;

-- Create applications table
CREATE TABLE IF NOT EXISTS applications (
    id VARCHAR(50) PRIMARY KEY,
    date DATETIME NOT NULL,
    job VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    availability VARCHAR(100) NOT NULL,
    experience TEXT,
    why TEXT NOT NULL,
    resume VARCHAR(500),
    status ENUM('pending', 'reviewed', 'interview', 'hired', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create clothing inventory table
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(500),
    stock INT DEFAULT 1,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@thriftandfound.com');

-- Insert sample clothing items
INSERT INTO inventory (name, category, price, description, featured) VALUES
('Levi\'s Vintage Denim Jacket', 'outerwear', 32.00, 'Authentic 90s wash, perfectly broken-in, classic trucker style with original buttons.', TRUE),
('1980s Floral Maxi Dress', 'womens', 28.00, 'Bold botanical print, flowing silhouette, adjustable straps, perfect for summer days.', TRUE),
('Corduroy Button-Up Shirt', 'mens', 24.00, 'Rich earth tones, soft wide-wale corduroy, pearl snap buttons, fall wardrobe essential.', TRUE),
('Vintage Leather Satchel', 'accessories', 38.00, 'Genuine leather, brass hardware, spacious interior, perfect everyday bag.', FALSE),
('90s Neon Windbreaker', 'vintage', 28.00, 'Retro neon colors, lightweight nylon, adjustable hood, iconic 90s streetwear.', FALSE),
('High-Waisted Mom Jeans', 'womens', 26.00, 'Classic 90s fit, light wash, slight stretch, excellent condition.', FALSE);