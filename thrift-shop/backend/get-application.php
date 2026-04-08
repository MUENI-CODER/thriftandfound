<?php
header('Content-Type: application/json');
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

$applications = getApplications();
// Reverse to show newest first
$applications = array_reverse($applications);

echo json_encode(['applications' => $applications]);
?>