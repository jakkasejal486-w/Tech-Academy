<?php

session_start();

header("Content-Type: application/json");

include "db.php";


// --------------------------------------------------
// CHECK LOGIN
// --------------------------------------------------

if (!isset($_SESSION["student_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first.",
        "login_required" => true
    ]);

    exit();
}


$student_id =
    $_SESSION["student_id"];


// --------------------------------------------------
// CHECK REQUEST
// --------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit();
}


// --------------------------------------------------
// GET FORM DATA
// --------------------------------------------------

$fname =
    trim($_POST["fname"] ?? "");

$lname =
    trim($_POST["lname"] ?? "");

$dob =
    $_POST["dob"] ?? "";

$gender =
    $_POST["gender"] ?? "";

$email =
    trim($_POST["email"] ?? "");

$contact =
    trim($_POST["contact"] ?? "");

$education =
    $_POST["education"] ?? "";

$password =
    $_POST["password"] ?? "";

$confirmPassword =
    $_POST["confirmPassword"] ?? "";


// --------------------------------------------------
// BASIC VALIDATION
// --------------------------------------------------

if (
    empty($fname) ||
    empty($lname) ||
    empty($dob) ||
    empty($gender) ||
    empty($email) ||
    empty($contact) ||
    empty($education)
) {

    echo json_encode([
        "success" => false,
        "message" => "Please fill all required fields."
    ]);

    exit();
}


// --------------------------------------------------
// CHECK EMAIL
// --------------------------------------------------

// Make sure another student isn't already
// using this email.

$emailCheck = $conn->prepare(
    "SELECT id
     FROM student
     WHERE email = ?
     AND id != ?"
);

$emailCheck->bind_param(
    "si",
    $email,
    $student_id
);

$emailCheck->execute();

$emailResult =
    $emailCheck->get_result();


if ($emailResult->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "This email is already used by another account."
    ]);

    exit();
}

$emailCheck->close();


// --------------------------------------------------
// UPDATE PASSWORD
// --------------------------------------------------

if (!empty($password)) {


    if ($password !== $confirmPassword) {

        echo json_encode([
            "success" => false,
            "message" => "Passwords do not match."
        ]);

        exit();

    }


    $hashedPassword =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );


    $stmt = $conn->prepare(
        "UPDATE student
         SET
            Firstname = ?,
            lastname = ?,
            dob = ?,
            gender = ?,
            email = ?,
            contact = ?,
            password = ?,
            education = ?
         WHERE id = ?"
    );


    $stmt->bind_param(
        "ssssssssi",
        $fname,
        $lname,
        $dob,
        $gender,
        $email,
        $contact,
        $hashedPassword,
        $education,
        $student_id
    );

}


// --------------------------------------------------
// UPDATE WITHOUT PASSWORD
// --------------------------------------------------

else {

    $stmt = $conn->prepare(
        "UPDATE student
         SET
            Firstname = ?,
            lastname = ?,
            dob = ?,
            gender = ?,
            email = ?,
            contact = ?,
            education = ?
         WHERE id = ?"
    );


    $stmt->bind_param(
        "sssssssi",
        $fname,
        $lname,
        $dob,
        $gender,
        $email,
        $contact,
        $education,
        $student_id
    );

}


// --------------------------------------------------
// EXECUTE STUDENT UPDATE
// --------------------------------------------------

if (!$stmt->execute()) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to update student information."
    ]);

    exit();

}

$stmt->close();


// --------------------------------------------------
// UPDATE SESSION
// --------------------------------------------------

$_SESSION["firstname"] =
    $fname;

$_SESSION["lastname"] =
    $lname;

$_SESSION["email"] =
    $email;


// --------------------------------------------------
// COURSES
// --------------------------------------------------

$selectedCourses =
    isset($_POST["course"])
    ? $_POST["course"]
    : [];


// Delete old courses

$deleteCourses =
    $conn->prepare(
        "DELETE FROM student_courses
         WHERE student_id = ?"
    );

$deleteCourses->bind_param(
    "i",
    $student_id
);

$deleteCourses->execute();

$deleteCourses->close();


// Add selected courses

if (!empty($selectedCourses)) {

    $courseStmt =
        $conn->prepare(
            "INSERT INTO student_courses
             (student_id, course_name)
             VALUES (?, ?)"
        );


    foreach ($selectedCourses as $course) {

        $courseStmt->bind_param(
            "is",
            $student_id,
            $course
        );

        $courseStmt->execute();

    }


    $courseStmt->close();

}


// --------------------------------------------------
// MOTIVATIONS
// --------------------------------------------------

$selectedMotivations =
    isset($_POST["motivation"])
    ? $_POST["motivation"]
    : [];


// Delete old motivations

$deleteMotivations =
    $conn->prepare(
        "DELETE FROM student_motivations
         WHERE student_id = ?"
    );

$deleteMotivations->bind_param(
    "i",
    $student_id
);

$deleteMotivations->execute();

$deleteMotivations->close();


// Add selected motivations

if (!empty($selectedMotivations)) {

    $motivationStmt =
        $conn->prepare(
            "INSERT INTO student_motivations
             (student_id, motivation)
             VALUES (?, ?)"
        );


    foreach (
        $selectedMotivations
        as $motivation
    ) {

        $motivationStmt->bind_param(
            "is",
            $student_id,
            $motivation
        );

        $motivationStmt->execute();

    }


    $motivationStmt->close();

}


// --------------------------------------------------
// SUCCESS
// --------------------------------------------------

echo json_encode([
    "success" => true,
    "message" => "Profile updated successfully."
]);


$conn->close();

?>