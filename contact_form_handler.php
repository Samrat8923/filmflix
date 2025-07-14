<?php
// Include PHPMailer's necessary files
require 'src/PHPMailer.php';
require 'src/Exception.php';
require 'src/SMTP.php';

// Use PHPMailer's classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get data from the contact form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Create a new PHPMailer instance for sending to the admin (you)
    $mail = new PHPMailer(true);

    try {
        // Server settings for sending email to admin (you)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'ayushsharma1854@gmail.com';  // Your Gmail address
        $mail->Password = 'hjvp rixr zgcb ggif';  // Use your app password (not your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable STARTTLS encryption
        $mail->Port = 587;  // Port 587 for STARTTLS

        // Recipients (send to the admin, yourself)
        $mail->setFrom($email, $name);  // Sender's email and name
        $mail->addAddress('ayushsharma1854@gmail.com');  // Recipient's email (your email)

        // Content (message that the admin (you) will receive)
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = 'New Contact Form Submission: ' . $subject;
        $mail->Body    = "<strong>Name:</strong> $name<br><strong>Email:</strong> $email<br><strong>Message:</strong><br>$message";

        // Send the email to admin (you)
        $mail->send();
        
        // Now create a second PHPMailer instance to send a confirmation email to the user
        $mail2 = new PHPMailer(true);

        // Server settings for sending email to the user
        $mail2->isSMTP();
        $mail2->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
        $mail2->SMTPAuth = true;
        $mail2->Username = 'ayushsharma1854@gmail.com';  // Your Gmail address
        $mail2->Password = 'hjvp rixr zgcb ggif';  // Use your app password
        $mail2->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable STARTTLS encryption
        $mail2->Port = 587;  // Port 587 for STARTTLS

        // Recipients (send confirmation to the user)
        $mail2->setFrom('ayushsharma1854@gmail.com', 'FilmFlix Support');  // Your email address as sender
        $mail2->addAddress($email);  // User's email address (provided in the form)

        // Content (confirmation message to the user)
        $mail2->isHTML(true);  // Set email format to HTML
        $mail2->Subject = 'Thank you for your submission, ' . $name;
        $mail2->Body    = "<strong>Hello $name,</strong><br><br>Thank you for contacting us. We have received your message and will get back to you within 24 hours.<br><br><strong>Your Message:</strong><br>$message";

        // Send the confirmation email to the user
        $mail2->send();

        // Success message
        echo 'Thank you! We will contact you within 24 hours.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
