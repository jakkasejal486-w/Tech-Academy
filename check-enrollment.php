 <?php

session_start();

header("Content-Type: application/json");

include "db.php";


/* CHECK LOGIN */

if (!isset($_SESSION["student_id"])) {

    echo json_encode([
        "logged_in" => false,
        "courses" => []
    ]);

    exit();
}


$student_id = $_SESSION["student_id"];


/* GET COURSES */

$stmt = $conn->prepare(
    "SELECT course_name
     FROM student_courses
     WHERE student_id = ?"
);


if (!$stmt) {

    echo json_encode([
        "logged_in" => true,
        "courses" => [],
        "error" => $conn->error
    ]);

    exit();
}


$stmt->bind_param(
    "i",
    $student_id
);


$stmt->execute();

$result = $stmt->get_result();


$courses = [];


while ($row = $result->fetch_assoc()) {

    $courses[] = $row["course_name"];

}


echo json_encode([
    "logged_in" => true,
    "courses" => $courses
]);


$stmt->close();

$conn->close();

?>