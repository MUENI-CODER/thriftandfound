<?php
// Configuration file
session_start();

// Email settings - UPDATE THESE WITH YOUR EMAIL
define('ADMIN_EMAIL', 'mueniemily521@gmail.com'); // Change this to your email
define('SITE_NAME', 'Thrift & Found');

// Applications file path
define('APPLICATIONS_FILE', __DIR__ . '/../applications.json');

// Admin credentials (in production, use a database and hashed passwords)
define('ADMIN_USERNAME', 'EMILIA');
define('ADMIN_PASSWORD', '0042027'); // Change this in production!

// Function to get applications
function getApplications() {
    if (file_exists(APPLICATIONS_FILE)) {
        $content = file_get_contents(APPLICATIONS_FILE);
        return json_decode($content, true) ?: [];
    }
    return [];
}

// Function to save applications
function saveApplications($applications) {
    file_put_contents(APPLICATIONS_FILE, json_encode($applications, JSON_PRETTY_PRINT));
}

// Function to send email
function sendEmail($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . SITE_NAME . " <noreply@" . $_SERVER['HTTP_HOST'] . ">" . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}
?>