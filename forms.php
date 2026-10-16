  ```php
<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $contact = $_POST["contact"];
    $otp = $_POST["otp"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $education = $_POST["education"];

    // Check password and confirm password
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash password before saving
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Get selected courses
    $courses = isset($_POST["course"])
        ? $_POST["course"]
        : [];

    // Get selected motivations
    $motivations = isset($_POST["motivation"])
        ? $_POST["motivation"]
        : [];


    // Check whether email already exists
    $checkEmail = $conn->prepare(
        "SELECT id FROM student WHERE email = ?"
    );

    $checkEmail->bind_param("s", $email);

    $checkEmail->execute();

    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {

        die("This email is already registered.");

    }


    // Insert student information
    $stmt = $conn->prepare(
        "INSERT INTO student
        (Firstname, lastname, dob, gender, email, contact, otp, password, education)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssssssss",
        $fname,
        $lname,
        $dob,
        $gender,
        $email,
        $contact,
        $otp,
        $hashedPassword,
        $education
    );


    // Execute student insertion
    if ($stmt->execute()) {

        // Get the newly created student ID
        $student_id = $stmt->insert_id;


        // --------------------------------
        // SAVE SELECTED COURSES
        // --------------------------------

        if (!empty($courses)) {

            $courseStmt = $conn->prepare(
                "INSERT INTO student_courses
                (student_id, course_name)
                VALUES (?, ?)"
            );

            foreach ($courses as $course) {

                $courseStmt->bind_param(
                    "is",
                    $student_id,
                    $course
                );

                $courseStmt->execute();
            }

            $courseStmt->close();
        }


        // --------------------------------
        // SAVE SELECTED MOTIVATIONS
        // --------------------------------

        if (!empty($motivations)) {

            $motivationStmt = $conn->prepare(
                "INSERT INTO student_motivations
                (student_id, motivation)
                VALUES (?, ?)"
            );

            foreach ($motivations as $motivation) {

                $motivationStmt->bind_param(
                    "is",
                    $student_id,
                    $motivation
                );

                $motivationStmt->execute();
            }

            $motivationStmt->close();
        }


        // --------------------------------
        // REGISTRATION SUCCESSFUL
        // --------------------------------

        header("Location: success.html");
        exit();

    } else {

        echo "Registration failed: " . $stmt->error;
    }


    // Close statements
    $stmt->close();
    $checkEmail->close();

}


// Close database connection
$conn->close();

?>
```
