<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../../vendor/autoload.php';


class Email {
        public function send($name, $senderEmail, $message, $receiverEmail) {
            $mail = new PHPMailer(true);
    
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "himeshdharmawansha1119@gmail.com"; // Your Gmail
                $mail->Password = "rfut gfju lqcs wwkx"; // App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
    
                // Sender and recipient
                $mail->setFrom($receiverEmail, "Himesh");
                $mail->addAddress($receiverEmail, "Himesh");
    
                // Email content
                $mail->isHTML(true);
                $mail->Subject = "New Contact Form Submission";
                $mail->Body = "
                    <h3>New Contact Message</h3>
                    <p><strong>Name:</strong> $name</p>
                    <p><strong>Email:</strong> $senderEmail</p>
                    <p><strong>Message:</strong><br>$message</p>
                ";
                $mail->AltBody = "Name: $name\nEmail: $senderEmail\nMessage:\n$message";
    
                // Send
                $mail->send();
                return "Message sent successfully.";
            } catch (Exception $e) {
                return "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
           }
    }
}

?>