<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $entered_otp = trim($_POST["otp"]);

    if (!isset($_SESSION["otp"])) {
        die("OTP expired or not generated.");
    }

    // OTP valid for 5 minutes
    if (time() - $_SESSION["otp_time"] > 300) {

        unset($_SESSION["otp"]);
        unset($_SESSION["otp_time"]);

        die("OTP expired. Please request a new OTP.");

    }

    if ($entered_otp == $_SESSION["otp"]) {

        // OTP verified
        $_SESSION["otp_verified"] = true;

        unset($_SESSION["otp"]);
        unset($_SESSION["otp_time"]);

        echo "<h2>OTP Verified Successfully!</h2>";
        echo "<p>You can continue your registration.</p>";

        // Later we can redirect to registration completion
        // header("Location: registration.html");

    } else {

        echo "<h2>Invalid OTP</h2>";
        echo "<p>Please enter the correct OTP.</p>";

    }
}
?>