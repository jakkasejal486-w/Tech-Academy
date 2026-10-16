<?php

session_start();

header("Content-Type: application/json");

include "db.php";


// Check login
if (!isset($_SESSION["student_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first.",
        "login_required" => true
    ]);

    exit();
}


$student_id = $_SESSION["student_id"];


// --------------------------------------------------
// GET STUDENT
// --------------------------------------------------

$stmt = $conn->prepare(
    "SELECT
        id,
        Firstname,
        lastname,
        dob,
        gender,
        email,
        contact,
        education
     FROM student
     WHERE id = ?"
);

$stmt->bind_param("i", $student_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows != 1) {

    echo json_encode([
        "success" => false,
        "message" => "Student account not found."
    ]);

    exit();
}


$student = $result->fetch_assoc();


// --------------------------------------------------
// GET COURSES
// --------------------------------------------------

$courses = [];

$courseStmt = $conn->prepare(
    "SELECT course_name
     FROM student_courses
     WHERE student_id = ?"
);

$courseStmt->bind_param(
    "i",
    $student_id
);

$courseStmt->execute();

$courseResult =
    $courseStmt->get_result();


while ($row = $courseResult->fetch_assoc()) {

    $courses[] =
        $row["course_name"];

}


$courseStmt->close();


// --------------------------------------------------
// GET MOTIVATIONS
// --------------------------------------------------

$motivations = [];

$motivationStmt = $conn->prepare(
    "SELECT motivation
     FROM student_motivations
     WHERE student_id = ?"
);

$motivationStmt->bind_param(
    "i",
    $student_id
);

$motivationStmt->execute();

$motivationResult =
    $motivationStmt->get_result();


while (
    $row =
    $motivationResult->fetch_assoc()
) {

    $motivations[] =
        $row["motivation"];

}


$motivationStmt->close();


// --------------------------------------------------
// SEND DATA TO JAVASCRIPT
// --------------------------------------------------

echo json_encode([

    "success" => true,

    "student" => [

        "firstname" =>
            $student["Firstname"],

        "lastname" =>
            $student["lastname"],

        "dob" =>
            $student["dob"],

        "gender" =>
            $student["gender"],

        "email" =>
            $student["email"],

        "contact" =>
            $student["contact"],

        "education" =>
            $student["education"]

    ],

    "courses" =>
        $courses,

    "motivations" =>
        $motivations

]);


$stmt->close();

$conn->close();

?>