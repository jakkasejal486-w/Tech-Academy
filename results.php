<?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

include "db.php";

$student_id = $_SESSION["student_id"];

$stmt = $conn->prepare(
    "SELECT course_name, score, total_questions
     FROM quiz_results
     WHERE student_id = ?
     ORDER BY id DESC"
);

$stmt->bind_param("i", $student_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>My Results</title>

<style>

body{
    background-color:black;
    color:white;
    font-family:Arial;
    margin:0;
    padding:100px 30px;
}

.container{
    width:850px;
    max-width:95%;
    margin:auto;

    padding:40px;

    border:2px solid #40E0D0;
    border-radius:15px;

    background-color:#080808;

    text-align:center;
}

h1{
    color:#40E0D0;
}

.result{
    background-color:#1e3a5f;

    padding:20px;

    margin:15px 0;

    border-radius:8px;

    font-size:18px;
}

.back{
    display:inline-block;

    margin-top:25px;

    padding:12px 25px;

    background-color:#40E0D0;

    color:black;

    text-decoration:none;

    border-radius:7px;

    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h1>📊 My Results</h1>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<div class='result'>";

        echo "📚 Course: " .
             htmlspecialchars($row["course_name"]);

        echo "<br><br>";

        echo "🏆 Score: " .
             htmlspecialchars($row["score"]) .
             " / " .
             htmlspecialchars($row["total_questions"]);

        echo "</div>";

    }

} else {

    echo "<p>You have not attempted any quiz yet.</p>";

}

?>

<a href="student-dashboard.php" class="back">
⬅ Back to Dashboard
</a>

</div>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>