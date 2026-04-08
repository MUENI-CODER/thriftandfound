<?php
header('Content-Type: application/json');
require_once 'config.php';
require_once 'email-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
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
    
    // Validate email format
    if (!filter_var($application['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }
    
    // Save to applications file
    $applications = getApplications();
    $applications[] = $application;
    saveApplications($applications);
    
    // Send email notification to admin using SMTP
    $emailSubject = "New Job Application: " . $application['job'];
    $emailMessage = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h2 { color: #1e3a2f; border-bottom: 2px solid #ffd966; padding-bottom: 10px; }
            .details { background: #f5f0e6; padding: 15px; border-radius: 10px; margin: 15px 0; }
            .label { font-weight: bold; color: #1e3a2f; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>New Job Application Received</h2>
            <p>A new application has been submitted for the position of <strong>" . htmlspecialchars($application['job']) . "</strong>.</p>
            <div class='details'>
                <p><span class='label'>Position:</span> " . htmlspecialchars($application['job']) . "</p>
                <p><span class='label'>Name:</span> " . htmlspecialchars($application['name']) . "</p>
                <p><span class='label'>Email:</span> " . htmlspecialchars($application['email']) . "</p>
                <p><span class='label'>Phone:</span> " . htmlspecialchars($application['phone']) . "</p>
                <p><span class='label'>Availability:</span> " . htmlspecialchars($application['availability']) . "</p>
                <p><span class='label'>Experience:</span><br>" . nl2br(htmlspecialchars($application['experience'])) . "</p>
                <p><span class='label'>Why they want to join:</span><br>" . nl2br(htmlspecialchars($application['why'])) . "</p>";
                
    if (!empty($application['resume'])) {
        $emailMessage .= "<p><span class='label'>Resume Link:</span> <a href='" . htmlspecialchars($application['resume']) . "'>" . htmlspecialchars($application['resume']) . "</a></p>";
    }
    
    $emailMessage .= "
            </div>
            <p>Log in to the admin panel to review and update the application status.</p>
            <p><a href='http://" . $_SERVER['HTTP_HOST'] . "/admin.html'>View in Admin Panel</a></p>
        </div>
    </body>
    </html>";
    
    sendEmailSMTP(ADMIN_EMAIL, $emailSubject, $emailMessage);
    
    // Send confirmation email to applicant
    $applicantSubject = "Application Received - Thrift & Found";
    $applicantMessage = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h2 { color: #1e3a2f; }
            .highlight { background: #ffd966; padding: 20px; border-radius: 10px; text-align: center; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Thank You for Applying, " . htmlspecialchars($application['name']) . "!</h2>
            <p>We have received your application for the position of <strong>" . htmlspecialchars($application['job']) . "</strong>.</p>
            <div class='highlight'>
                <p>Our team will review your application and get back to you within 3-5 business days.</p>
            </div>
            <p>If you have any questions, feel free to contact us at " . ADMIN_EMAIL . ".</p>
            <p>Best regards,<br>The Thrift & Found Team</p>
        </div>
    </body>
    </html>";
    
    sendEmailSMTP($application['email'], $applicantSubject, $applicantMessage);
    
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
}
?>