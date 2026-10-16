 <?php

session_start();

include "db.php";

header("Content-Type: application/json");


 

if (!isset($_SESSION["student_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit();
}


 

$student_id = $_SESSION["student_id"];


 

$course_name = $_POST["course_name"] ?? "";

$score = intval($_POST["score"] ?? 0);

$total_questions = intval($_POST["total_questions"] ?? 0);


 

if (
    $course_name == "" ||
    $total_questions <= 0
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid quiz data."
    ]);

    exit();

}


/* Calculate percentage */

$percentage =
    ($score / $total_questions) * 100;


/* Insert result */

$stmt = $conn->prepare(
    "INSERT INTO quiz_results
    (
        student_id,
        course_name,
        score,
        total_questions,
        percentage
    )
    VALUES (?, ?, ?, ?, ?)"
);


if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "SQL prepare error: " . $conn->error
    ]);

    exit();

}


/* Bind values */

$stmt->bind_param(
    "isiid",
    $student_id,
    $course_name,
    $score,
    $total_questions,
    $percentage
);


/* Execute */

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Quiz result saved successfully."
    ]);

}

else {

    echo json_encode([
        "success" => false,
        "message" => "SQL error: " . $stmt->error
    ]);

}


$stmt->close();

$conn->close();

?>