<?php

require 'vendor/autoload.php';
use Symfony\Component\Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

$name = trim($_POST['contact-name']);
$phone = trim($_POST['contact-phone']);
$email = trim($_POST['contact-email']);
$message = trim($_POST['contact-message']);

if ($name == "") {
    $msg['err'] = "\n Name can not be empty!";
    $msg['field'] = "contact-name";
    $msg['code'] = FALSE;
} else if ($phone == "") {
    $msg['err'] = "\n Phone number can not be empty!";
    $msg['field'] = "contact-phone";
    $msg['code'] = FALSE;
} else if (!preg_match("/^[0-9 \\-\\+]{4,17}$/i", trim($phone))) {
    $msg['err'] = "\n Please put a valid phone number!";
    $msg['field'] = "contact-phone";
    $msg['code'] = FALSE;
} else if ($email == "") {
    $msg['err'] = "\n Email can not be empty!";
    $msg['field'] = "contact-email";
    $msg['code'] = FALSE;
} else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $msg['err'] = "\n Please put a valid email address!";
    $msg['field'] = "contact-email";
    $msg['code'] = FALSE;
} else if ($message == "") {
    $msg['err'] = "\n Message can not be empty!";
    $msg['field'] = "contact-message";
    $msg['code'] = FALSE;
} else {
    $to = 'paul.fresnais@gmail.com';
    $subject = 'Prise de contact - webcv';
    $_message = '<html><head></head><body>';
    $_message .= '<p>Name: ' . $name . '</p>';
    $_message .= '<p>Message: ' . $phone . '</p>';
    $_message .= '<p>Email: ' . $email . '</p>';
    $_message .= '<p>Message: ' . $message . '</p>';
    $_message .= '</body></html>';

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From:  inbio <contact@example.com>' . "\r\n";

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through //'ssl0.ovh.net'
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = $_ENV["EMAIL"];
        $mail->Password = $_ENV["PASSWORD"];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email);
        $mail->addAddress("paul.fresnais@outlook.fr");     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subjects;
        //$mail->Body = $message;
        $mail->Body = $message . " " . $email . " " . $phone;

        $mail->send();
        echo 'Message has been sent';
        $msg['success'] = "\n Email has been sent successfully.";
        $msg['code'] = true;
    } catch (Exception $e) {
        $msg['success'] = "\n Message could not be sent. Mailer Error";
        $msg['code'] = false;
    }
}
echo json_encode($msg);