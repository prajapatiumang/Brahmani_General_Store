<?php
// Email configuration
$to = "prajapatiumang000@gmail.com"; // Support email from contact.html
$subject_prefix = "New Contact Form Submission - ";

// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    // Create email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Create email body
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
            .container { background-color: #fff; padding: 20px; border-radius: 5px; max-width: 600px; margin: 0 auto; }
            .header { color: #00f5ff; border-bottom: 2px solid #00f5ff; padding-bottom: 10px; margin-bottom: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #333; }
            .value { color: #666; margin-top: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Contact Form Submission from Brahmani General Store Website</h2>
            </div>
            
            <div class='field'>
                <div class='label'>Sender Name:</div>
                <div class='value'>" . $name . "</div>
            </div>
            
            <div class='field'>
                <div class='label'>Sender Email:</div>
                <div class='value'>" . $email . "</div>
            </div>
            
            <div class='field'>
                <div class='label'>Subject:</div>
                <div class='value'>" . $subject . "</div>
            </div>
            
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br($message) . "</div>
            </div>
            
            <hr>
            <p style='color: #999; font-size: 12px; margin-top: 20px;'>
                This email was sent from the Brahmani General Store website contact form.
            </p>
        </div>
    </body>
    </html>
    ";
    
    // Send email
    $final_subject = $subject_prefix . $subject;
    
    if (mail($to, $final_subject, $email_body, $headers)) {
        // Also send confirmation email to user
        $user_headers = "MIME-Version: 1.0" . "\r\n";
        $user_headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $user_headers .= "From: noreply@brahmanigeneralstore.com" . "\r\n";
        
        $confirmation_body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
                .container { background-color: #fff; padding: 20px; border-radius: 5px; max-width: 600px; margin: 0 auto; }
                .header { color: #00f5ff; border-bottom: 2px solid #00f5ff; padding-bottom: 10px; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Thank You for Contacting Brahmani General Store</h2>
                </div>
                
                <p>Dear " . $name . ",</p>
                
                <p>We have received your message and will get back to you as soon as possible. Our team typically responds within 24 hours.</p>
                
                <p><strong>Your Message Details:</strong></p>
                <ul>
                    <li>Subject: " . $subject . "</li>
                    <li>Submitted at: " . date('Y-m-d H:i:s') . "</li>
                </ul>
                
                <p>If you have any urgent matters, please call us at:</p>
                <ul>
                    <li>Customer Support: +91 9925078686</li>
                    <li>Business: +91 9016626581</li>
                </ul>
                
                <p style='margin-top: 30px; color: #666;'>
                    Best regards,<br>
                    <strong>Brahmani General Store Team</strong><br>
                    Palanpur, Gujarat
                </p>
            </div>
        </body>
        </html>
        ";
        
        mail($email, "Thank You for Your Message", $confirmation_body, $user_headers);
        
        echo json_encode(['success' => true, 'message' => 'Message sent successfully! We will contact you soon.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
