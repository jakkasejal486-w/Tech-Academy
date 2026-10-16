  <?php

session_start();

// PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Check POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get email
    $email = trim($_POST["email"]);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Generate 6 digit OTP
    $otp = random_int(100000, 999999);

    // Store OTP in session
    $_SESSION["otp"] = $otp;
    $_SESSION["otp_email"] = $email;
    $_SESSION["otp_time"] = time();

    // Create PHPMailer
    $mail = new PHPMailer(true);

    try {

        // Gmail SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // Your Gmail
        $mail->Username = 'jakkasejal486@gmail.com';

        // Your NEW Google App Password
        $mail->Password = 'yxyv ipha legb kjzx';

        // Gmail encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // SSL settings for local XAMPP testing
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Sender
        $mail->setFrom(
            'jakkasejal486@gmail.com',
            'Tech Learning Academy'
        );

        // Receiver
        $mail->addAddress($email);

        // HTML email
        $mail->isHTML(true);

        $mail->Subject = 'Tech Learning Academy - OTP Verification';

        // Email body
        $mail->Body = "

        <div style='
            font-family: Arial;
            max-width: 500px;
            margin: auto;
            padding: 25px;
            border: 1px solid #40E0D0;
            border-radius: 10px;
        '>

            <h2 style='color:#1e3a5f;'>
                Tech Learning Academy
            </h2>

            <p>Hello,</p>

            <p>
                Your OTP for registration is:
            </p>

            <h1 style='
                letter-spacing: 8px;
                text-align: center;
                color:#1e3a5f;
            '>
                $otp
            </h1>

            <p>
                This OTP is valid for <b>5 minutes</b>.
            </p>

            <p>
                Please do not share this OTP with anyone.
            </p>

            <br>

            <p>
                Thank you,<br>
                <b>Tech Learning Academy</b>
            </p>

        </div>

        ";

        // Plain text version
        $mail->AltBody =
            "Your Tech Learning Academy OTP is: $otp";


        // Send email
        $mail->send();


        // Tell JavaScript that OTP was successfully sent
        echo "OTP_SENT";
        exit;


    } catch (Exception $e) {

        echo "OTP could not be sent.<br><br>";
        echo "Mailer Error: " . $mail->ErrorInfo;
        exit;
    }
}

?>