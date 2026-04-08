<?php
header('Content-Type: application/json');
session_start();
require_once 'db-config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Validate
    if (empty($name) || empty($category) || empty($price) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    // Handle image upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../images/clothing/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
        $targetFile = $uploadDir . $fileName;
        
        // Check file size (5MB max)
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File is too large. Max 5MB.']);
            exit;
        }
        
        // Allow only certain file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, WEBP, and GIF files are allowed.']);
            exit;
        }
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $fileName;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please upload an image.']);
        exit;
    }
    
    // Insert into database
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            INSERT INTO inventory (name, category, price, description, image, featured) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([$name, $category, $price, $description, $imagePath, $featured]);
        
        echo json_encode(['success' => true, 'message' => 'Item added successfully!']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
        error_log("Add item error: " . $e->getMessage());
    }
}
?>