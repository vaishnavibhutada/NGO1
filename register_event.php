<?php

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $event = htmlspecialchars($_POST['event']);

    // Set the event date and time dynamically
    $eventDate = date('F j, Y', strtotime('+7 days')); // Adjust '+7 days' to set how many days after registration the event is
    $eventTime = date('h:i A', strtotime('10:00 AM')); // Adjust the time as needed or make it dynamic based on conditions
    $eventLocation = '123 Event Venue, Main Street, City'; // Replace with actual location

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vaishnavibhutada514@gmail.com';  // Your Gmail address
        $mail->Password = 'klqq yalt sdgp comc';  // Your Gmail password or app-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Event Team');
        $mail->addAddress($email, $name); // Add recipient's email and name

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Event Registration Confirmation';
        $mail->Body = 'Dear ' . $name . ',<br><br>' .
                      'Thank you for registering for ' . $event . '. We are delighted to have you join us and look forward to an engaging and enriching experience together.<br><br>' .
                      'Event Details:<br>' .
                      '<strong>Date:</strong> ' . $eventDate . '<br>' .
                      '<strong>Time:</strong> ' . $eventTime . '<br>' .
                      '<strong>Location:</strong> ' . $eventLocation . '<br><br>' .
                      'Should you have any questions or need further assistance, please feel free to contact us at support@yourorganization.com or call (123) 456-7890.<br><br>' .
                      'We appreciate your interest and participation and can’t wait to welcome you!<br><br>' .
                      'Best regards,<br>' .
                      'The Event Team';

        $mail->send();
        echo 'Registration successful! A confirmation email has been sent to ' . $email . '.';
    } catch (Exception $e) {
        echo 'Registration successful, but the email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>