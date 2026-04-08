<?php
header('Content-Type: application/json');
require_once 'config.php';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === EMILIA && $password === 0042027) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: ../admin.html');
        exit;
    } else {
        // Redirect back to login with error
        header('Location: ../admin-login.html?error=invalid');
        exit;
    }
}

// Check authentication status (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        echo json_encode(['logged_in' => true]);
    } else {
        echo json_encode(['logged_in' => false]);
    }
    exit;
}
?>