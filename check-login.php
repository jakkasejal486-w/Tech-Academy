<?php

session_start();

header("Content-Type: application/json");

if (isset($_SESSION["student_id"])) {

    echo json_encode([
        "logged_in" => true
    ]);

} else {

    echo json_encode([
        "logged_in" => false
    ]);
}

?>