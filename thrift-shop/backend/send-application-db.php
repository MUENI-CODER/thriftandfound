<?php
header('Content-Type: application/json');
require_once 'db-config.php';
require_once 'email-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application = [
        'id' => uniqid(),
        'date' => date('Y-m-d H:i:s'),
        'job' => $_POST['job'] ?? '',
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'availability' => $_POST['availability'] ?? '',
        'experience' => $_POST['experience'] ?? '',
        'why' => $_POST['why'] ?? '',
        'resume' => $_POST['resume'] ?? '',
        'status' => 'pending'
    ];
    
    // Validate required fields
    if (empty($application['job']) || empty($application['name']) || empty($application['email']) || 
        empty($application['phone']) || empty($application['availability']) || empty($application['why'])) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    try {
        $pdo = getDBConnection();
        
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO applications (id, date, job, name, email, phone, availability, experience, why, resume, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $application['id'],
            $application['date'],
            $application['job'],
            $application['name'],
            $application['email'],
            $application['phone'],
            $application['availability'],
            $application['experience'],
            $application['why'],
            $application['resume'],
            $application['status']
        ]);
        
        // Send emails using SMTP
        sendApplicationEmails($application);
        
        echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
        error_log("Application error: " . $e->getMessage());
    }
}

function sendApplicationEmails($application) {
    // Admin email
    $adminMessage = "
    <html>
    <body>
        <h2>New Job Application: " . htmlspecialchars($application['job']) . "</h2>
        <p><strong>Name:</strong> " . htmlspecialchars($application['name']) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($application['email']) . "</p>
        <p><strong>Phone:</strong> " . htmlspecialchars($application['phone']) . "</p>
        <p><strong>Availability:</strong> " . htmlspecialchars($application['availability']) . "</p>
        <p><strong>Why:</strong><br>" . nl2br(htmlspecialchars($application['why'])) . "</p>
    </body>
    </html>";
    
    sendEmailSMTP(ADMIN_EMAIL, "New Job Application: " . $application['job'], $adminMessage);
    
    // Applicant confirmation
    $applicantMessage = "
    <html>
    <body>
        <h2>Thank You for Applying!</h2>
        <p>Dear " . htmlspecialchars($application['name']) . ",</p>
        <p>We have received your application for the position of <strong>" . htmlspecialchars($application['job']) . "</strong>.</p>
        <p>Our team will review your application and get back to you within 3-5 business days.</p>
        <p>Best regards,<br>The Thrift & Found Team</p>
    </body>
    </html>";
    
    sendEmailSMTP($application['email'], "Application Received - Thrift & Found", $applicantMessage);
}
?>