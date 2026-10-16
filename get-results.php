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

    "SELECT
        course_name,
        score,
        total_questions,
        percentage,
        quiz_date

     FROM quiz_results

     WHERE student_id = ?

     ORDER BY quiz_date DESC"

);


$stmt->bind_param(
    "i",
    $student_id
);


$stmt->execute();


$result =
$stmt->get_result();


$results = [];


while (
    $row =
    $result->fetch_assoc()
){

    $results[] = $row;

}


echo json_encode([

    "success" => true,

    "results" => $results

]);


$stmt->close();

$conn->close();

?>