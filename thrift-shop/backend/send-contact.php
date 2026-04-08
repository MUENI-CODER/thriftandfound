<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }
    
    // Send email to admin
    $emailSubject = "Contact Form: " . $subject;
    $emailMessage = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h2 { color: #1e3a2f; }
            .details { background: #f5f0e6; padding: 15px; border-radius: 10px; margin: 15px 0; }
            .label { font-weight: bold; color: #1e3a2f; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>New Contact Form Message</h2>
            <div class='details'>
                <p><span class='label'>From:</span> " . htmlspecialchars($name) . " (" . htmlspecialchars($email) . ")</p>
                <p><span class='label'>Subject:</span> " . htmlspecialchars($subject) . "</p>
                <p><span class='label'>Message:</span><br>" . nl2br(htmlspecialchars($message)) . "</p>
            </div>
        </div>
    </body>
    </html>";
    
    $sent = sendEmail(ADMIN_EMAIL, $emailSubject, $emailMessage);
    
    if ($sent) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
    }
}
?>