 <?php

session_start();

include "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["student_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "NOT LOGGED IN"
    ]);

    exit;
}

$student_id = $_SESSION["student_id"];
$course_name = $_POST["course_name"] ?? "";

if ($course_name == "") {

    echo json_encode([
        "success" => false,
        "message" => "COURSE NAME EMPTY"
    ]);

    exit;
}

$sql = "INSERT INTO student_courses (student_id, course_name)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "PREPARE ERROR: " . $conn->error
    ]);

    exit;
}

$stmt->bind_param(
    "is",
    $student_id,
    $course_name
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "ENROLLMENT SAVED",
        "student_id" => $student_id,
        "course" => $course_name
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "INSERT ERROR: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>