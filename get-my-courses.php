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


$student_id =
$_SESSION["student_id"];


$stmt = $conn->prepare(

    "SELECT course_name
     FROM student_courses
     WHERE student_id = ?"

);


$stmt->bind_param(
    "i",
    $student_id
);


$stmt->execute();


$result =
$stmt->get_result();


$courses = [];


while (
    $row =
    $result->fetch_assoc()
){

    $courses[] =
    $row["course_name"];

}


echo json_encode([

    "success" => true,

    "courses" => $courses

]);


$stmt->close();

$conn->close();

?>