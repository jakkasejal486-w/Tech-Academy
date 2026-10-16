 <?php

session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.html");
    exit();
}

include "db.php";

$student_id = $_SESSION["student_id"];


/* GET ALL ELIGIBLE QUIZ RESULTS */

$stmt = $conn->prepare(
    "SELECT id, course_name, score, total_questions, percentage
     FROM quiz_results
     WHERE student_id = ?
     AND score >= 18
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

<title>My Certificates - Tech Learning Academy</title>

<style>

*{
    box-sizing:border-box;
}

body{
    background-color:black;
    color:white;
    margin:0;
    padding:90px 30px 40px;
 
}


/* NAVIGATION */

nav{
    position:fixed;
    top:0;
    left:0;
    right:0;
    width:100%;
    height:70px;

    background-color:#1e3a5f;

    padding:10px 20px;

    display:flex;
    align-items:center;

    gap:10px;

    z-index:1000;
}

nav a{
    color:#40E0D0;
    text-decoration:none;
    font-size:15px;
    font-weight:bold;
    white-space:nowrap;
}

nav a:hover{
    color:white;
}

nav p{
    color:white;
    font-size:20px;
    margin:0;
}


/* STUDENT MENU */

.student-dashboard{
    margin-left:auto;
    position:relative;
}

.student-logo{
    width:55px;
    height:55px;

    border-radius:50%;

    border:3px solid #40E0D0;

    background-color:#1e3a5f;

    color:white;

    font-size:27px;

    cursor:pointer;
}

.student-logo:hover{
    background-color:#40E0D0;
    color:#1e3a5f;
}

.student-menu{

    display:none;

    position:absolute;

    top:65px;
    right:0;

    width:210px;

    background-color:#1e3a5f;

    border-radius:10px;

    overflow:hidden;

    box-shadow:0 5px 20px rgba(0,0,0,0.5);

}

.student-menu.show{

    display:flex;
    flex-direction:column;

}

.student-menu a{

    color:#40E0D0;

    text-decoration:none;

    padding:15px 18px;

    font-size:16px;

    border-bottom:1px solid rgba(64,224,208,0.2);

}

.student-menu a:hover{

    background-color:#40E0D0;

    color:#1e3a5f;

}


/* MAIN */

.container{

    max-width:900px;

    margin:auto;

}

h1{

    text-align:center;

    color:#40E0D0;

    margin-bottom:10px;

    font-size:36px;

}

.subtitle{

    text-align:center;

    color:#ccc;

    margin-bottom:40px;

}


/* CERTIFICATE CARD */

.certificate-card{

    background-color:#111;

    border:2px solid #1e3a5f;

    border-radius:12px;

    padding:25px;

    margin-bottom:20px;

}

.certificate-card:hover{

    border-color:#40E0D0;

}

.certificate-card h2{

    color:#40E0D0;

    margin-top:0;

    font-size:25px;

}

.certificate-card p{

    font-size:18px;

    color:#ddd;

    margin:10px 0;

}

.status{

    color:#40E0D0;

    font-weight:bold;

}


/* BUTTONS */

.button-area{

    margin-top:20px;

}

.certificate-btn{

    display:inline-block;

    padding:12px 22px;

    background-color:#40E0D0;

    color:#1e3a5f;

    text-decoration:none;

    border-radius:8px;

    font-weight:bold;

    margin-right:10px;

}

.certificate-btn:hover{

    background-color:white;

}

.download-btn{

    background-color:#1e3a5f;

    color:#40E0D0;

    border:2px solid #40E0D0;

}

.download-btn:hover{

    background-color:#40E0D0;

    color:#1e3a5f;

}


/* NO CERTIFICATE */

.no-certificate{

    text-align:center;

    background-color:#111;

    padding:30px;

    border-radius:10px;

    border:2px solid #1e3a5f;

}

.no-certificate h2{

    color:#40E0D0;

}


/* MOBILE */

@media(max-width:700px){

    body{
        padding:90px 10px 30px;
    }

    nav a{
        font-size:10px;
    }

    nav p{
        font-size:13px;
    }

    .certificate-btn{

        display:block;

        text-align:center;

        margin:10px 0;

    }

}

</style>

</head>


<body>


<!-- NAVIGATION -->

<nav>

<p>|</p>

<a href="index.html">HOME</a>

<p>|</p>

<a href="information.html">INFO</a>

<p>|</p>

<a href="courses.html">COURSES</a>

<p>|</p>

<a href="login.html">LOGIN</a>

<p>|</p>

<a href="forms.html">REGISTRATION</a>

<p>|</p>

<a href="contact.html">📞 CONTACT US</a>


<div class="student-dashboard">

<button
class="student-logo"
type="button"
onclick="toggleStudentMenu()">

🎓

</button>


<div class="student-menu" id="studentMenu">

<a href="student-dashboard.php">
🏠 Dashboard
</a>

<a href="profile.php">
👤 My Profile
</a>

<a href="my-courses.php">
📚 My Courses
</a>

<a href="my-certificate.php">
🏆 My Certificate
</a>

<a href="results.php">
📊 My Results
</a>

<a href="logout.php">
🚪 Logout
</a>

</div>

</div>

</nav>


<!-- MAIN -->

<div class="container">

<h1>🎓 My Certificates</h1>

<p class="subtitle">
Your successfully completed courses
</p>


<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

?>

<div class="certificate-card">

<h2>

🏆

<?php

echo htmlspecialchars($row["course_name"]);

?>

Certificate

</h2>


<p>

<strong>Score:</strong>

<?php

echo $row["score"];

?>

/

<?php

echo $row["total_questions"];

?>

</p>


<p>

<strong>Status:</strong>

<span class="status">
Completed
</span>

</p>


<p>

<strong>Percentage:</strong>

<?php

echo number_format($row["percentage"],2);

?>%

</p>


<div class="button-area">


<!-- VIEW CERTIFICATE -->

<a
href="certificate.php?id=<?php echo (int)$row["id"]; ?>"
class="certificate-btn"
target="_blank">

🏆 VIEW CERTIFICATE

</a>


<!-- PRINT / DOWNLOAD -->

<a
href="certificate.php?id=<?php echo (int)$row["id"]; ?>&print=1"
class="certificate-btn download-btn"
target="_blank">

🖨 PRINT / DOWNLOAD

</a>


</div>

</div>


<?php

    }

}

else {

?>

<div class="no-certificate">

<h2>🏆 No Certificate Available</h2>

<p>

You need to score at least

<strong>18 / 20</strong>

in a quiz to receive a certificate.

</p>

</div>

<?php

}

?>

</div>


<script>

function toggleStudentMenu(){

    const menu =
        document.getElementById("studentMenu");

    menu.classList.toggle("show");

}

</script>


</body>

</html>