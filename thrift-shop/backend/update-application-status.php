<?php
header('Content-Type: application/json');
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['error' => 'Unauthorized']);
    http_response_code(401);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? '';
$status = $input['status'] ?? '';

if (empty($id) || empty($status)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Valid statuses
$validStatuses = ['pending', 'reviewed', 'interview', 'hired', 'rejected'];
if (!in_array($status, $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

// Update application
$applications = getApplications();
$updated = false;

foreach ($applications as &$app) {
    if ($app['id'] === $id) {
        $oldStatus = $app['status'] ?? 'pending';
        $app['status'] = $status;
        $updated = true;
        
        // Send email notification for status change
        if ($oldStatus !== $status) {
            $statusMessages = [
                'reviewed' => "Your application has been reviewed and is now being considered.",
                'interview' => "Congratulations! We would like to invite you for an interview.",
                'hired' => "Great news! We would like to offer you the position.",
                'rejected' => "Thank you for your interest, but we have decided to move forward with other candidates."
            ];
            
            if (isset($statusMessages[$status])) {
                $subject = "Application Status Update - Thrift & Found";
                $message = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .status { background: #ffd966; padding: 20px; border-radius: 10px; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Application Status Update</h2>
                        <p>Dear " . htmlspecialchars($app['name']) . ",</p>
                        <div class='status'>
                            <p>" . $statusMessages[$status] . "</p>
                        </div>
                        <p>Position: " . htmlspecialchars($app['job']) . "</p>
                        <p>If you have any questions, please contact us at " . mueniemily521@gmail.com. ".</p>
                        <p>Best regards,<br>The Thrift & Found Team</p>
                    </div>
                </body>
                </html>";
                
                sendEmail($app['email'], $subject, $message);
            }
        }
        break;
    }
}

if ($updated) {
    saveApplications($applications);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Application not found']);
}
?>