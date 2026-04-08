<?php
header('Content-Type: application/json');
require_once 'db-config.php';

// Function to calculate daily decreasing price
function getDailyPrice($original_price, $days_until_new_stock) {
    // Price decreases by 5% each day until new stock arrives
    $daily_discount = 0.05; // 5% decrease per day
    $current_price = $original_price * (1 - ($daily_discount * $days_until_new_stock));
    
    // Don't go below 30% of original price
    $min_price = $original_price * 0.30;
    return max($current_price, $min_price);
}

// Get all items with dynamic pricing
function getItemsWithDynamicPricing() {
    $pdo = getDBConnection();
    
    // Get inventory with stock and next stock date
    $stmt = $pdo->query("
        SELECT i.*, 
               DATEDIFF(COALESCE(next_stock_date, DATE_ADD(CURDATE(), INTERVAL 30 DAY)), CURDATE()) as days_until_stock
        FROM inventory i
        WHERE i.stock > 0 OR i.next_stock_date IS NOT NULL
    ");
    
    $items = $stmt->fetchAll();
    $result = [];
    
    foreach ($items as $item) {
        $base_price = $item['base_price'];
        $days_left = max(0, $item['days_until_stock']);
        
        // Calculate current price (decreases daily)
        $current_price = getDailyPrice($base_price, $days_left);
        
        // Calculate tomorrow's price
        $tomorrow_price = getDailyPrice($base_price, max(0, $days_left - 1));
        
        // Calculate price when new stock arrives (resets to base price)
        $new_stock_price = $base_price;
        
        $result[] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'category' => $item['category'],
            'description' => $item['description'],
            'image' => $item['image'],
            'stock' => $item['stock'],
            'base_price' => $base_price,
            'current_price' => round($current_price, 2),
            'tomorrow_price' => round($tomorrow_price, 2),
            'days_until_new_stock' => $days_left,
            'next_stock_date' => $item['next_stock_date'],
            'discount_percent' => round((1 - $current_price / $base_price) * 100),
            'price_decrease_today' => round($base_price - $current_price, 2)
        ];
    }
    
    return $result;
}

// API endpoint
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $items = getItemsWithDynamicPricing();
    echo json_encode(['success' => true, 'items' => $items]);
}
?>