  <?php

session_start();


/* CHECK LOGIN */

if (!isset($_SESSION["student_id"])) {

    header("Location: login.html");

    exit();

}


include "db.php";


$student_id = $_SESSION["student_id"];


/* GET CERTIFICATE ID */

$certificate_id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;


/* CHECK ID */

if ($certificate_id <= 0) {

    die("Invalid certificate ID.");

}


/* GET STUDENT */

$stmt = $conn->prepare(
    "SELECT Firstname, lastname
     FROM student
     WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $student_id
);

$stmt->execute();

$student_result = $stmt->get_result();

$student = $student_result->fetch_assoc();


if (!$student) {

    die("Student not found.");

}


/* GET CERTIFICATE */

$stmt2 = $conn->prepare(
    "SELECT id,
            course_name,
            score,
            total_questions,
            percentage
     FROM quiz_results
     WHERE id = ?
     AND student_id = ?
     AND score >= 18
     LIMIT 1"
);

$stmt2->bind_param(
    "ii",
    $certificate_id,
    $student_id
);

$stmt2->execute();

$certificate_result = $stmt2->get_result();

$certificate = $certificate_result->fetch_assoc();


/* CHECK CERTIFICATE */

if (!$certificate) {

    die("Certificate not found or you are not eligible for this certificate.");

}


/* DATE */

$completion_date = date("d F Y");


/* PRINT */

$print = isset($_GET["print"]) && $_GET["print"] == "1";

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

<?php

echo htmlspecialchars(
    $certificate["course_name"]
);

?>

Certificate - Tech Learning Academy

</title>


<style>

*{
    box-sizing:border-box;
}

body{

    margin:0;

    background:#111;

    font-family:Arial,sans-serif;

}


/* CERTIFICATE */

.certificate{

    width:900px;

    min-height:600px;

    margin:50px auto;

    background:white;

    border:15px solid #1e3a5f;

    outline:5px solid #40E0D0;

    padding:50px;

    text-align:center;

}


/* ACADEMY */

.certificate h1{

    color:#1e3a5f;

    font-size:45px;

    margin:0 0 10px;

}


/* TITLE */

.certificate h2{

    color:#40E0D0;

    font-size:32px;

    margin:25px 0;

}


/* NORMAL FONT */

.certificate p{

    color:#333;

    font-size:18px;

    line-height:1.6;

}


/* STUDENT NAME */

.student-name{

    font-size:38px;

    font-weight:bold;

    color:#1e3a5f;

    margin:25px 0;

}


/* COURSE */

.course{

    font-size:28px;

    font-weight:bold;

    color:#1e3a5f;

    margin:20px 0;

}


/* SCORE */

.score{

    margin-top:20px;

    font-size:20px;

}


/* DATE */

.date{

    margin-top:35px;

    color:#555;

}


/* BUTTONS */

.buttons{

    text-align:center;

    margin:25px auto;

}

button{

    padding:12px 25px;

    margin:5px;

    background:#40E0D0;

    border:none;

    border-radius:7px;

    font-size:16px;

    font-weight:bold;

    cursor:pointer;

}

button:hover{

    background:white;

    border:2px solid #40E0D0;

}


/* PRINT */

@media print{

    body{

        background:white;

    }

    .buttons{

        display:none;

    }

    .certificate{

        margin:0 auto;

        box-shadow:none;

    }

}


/* MOBILE */

@media(max-width:700px){

    .certificate{

        width:95%;

        min-height:auto;

        padding:30px 20px;

        border-width:8px;

    }

    .certificate h1{

        font-size:30px;

    }

    .certificate h2{

        font-size:25px;

    }

    .student-name{

        font-size:28px;

    }

    .course{

        font-size:23px;

    }

}

</style>

</head>


<body>


<!-- CERTIFICATE -->

<div class="certificate">


<h1>

TECH LEARNING ACADEMY

</h1>


<h2>

CERTIFICATE OF COMPLETION

</h2>


<p>

This certificate is proudly presented to

</p>


<div class="student-name">

<?php

echo htmlspecialchars(

    $student["Firstname"] . " " .
    $student["lastname"]

);

?>

</div>


<p>

for successfully completing the

</p>


<div class="course">

<?php

echo htmlspecialchars(
    $certificate["course_name"]
);

?>

COURSE

</div>


<p>

This certificate recognizes the successful completion
of the course requirements and demonstrates dedication
to learning technical skills.

</p>


<p class="score">


<strong>Quiz Score:</strong>

<?php

echo $certificate["score"];

?>

/

<?php

echo $certificate["total_questions"];

?>


&nbsp;&nbsp;&nbsp;


<strong>Percentage:</strong>

<?php

echo number_format(
    $certificate["percentage"],
    2
);

?>%


</p>


<p class="date">

<strong>Date of Completion:</strong>

<?php

echo $completion_date;

?>

</p>


</div>


<!-- BUTTONS -->

<div class="buttons">


<button onclick="window.print()">

🖨 PRINT / DOWNLOAD

</button>


<button onclick="window.close()">

✖ CLOSE

</button>


</div>


<?php

/* AUTO PRINT */

if ($print) {

?>

<script>

window.onload = function(){

    window.print();

};

</script>

<?php

}

?>


</body>

</html>