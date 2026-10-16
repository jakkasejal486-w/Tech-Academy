 <?php

session_start();

/* Check if student is logged in */

if (!isset($_SESSION["student_id"])) {

    header("Location: login.html");
    exit();

}


/* Get student information */

$firstname = $_SESSION["firstname"] ?? "";
$lastname  = $_SESSION["lastname"] ?? "";
$email     = $_SESSION["email"] ?? "";

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Student Dashboard - Tech Learning Academy</title>

<style>

*{
    box-sizing:border-box;
}

body{

    background-color:black;

    color:white;

    margin:0;

    padding:90px 30px 30px;
 

}


/* ================= NAVIGATION ================= */

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

    padding:0;

}


/* ================= STUDENT MENU ================= */

.student-dashboard{

    margin-left:auto;

    position:relative;

    flex-shrink:0;

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

    display:flex;

    align-items:center;

    justify-content:center;

}

.student-logo:hover{

    background-color:#40E0D0;

    color:#1e3a5f;

}


/* HIDDEN MENU */

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

    z-index:2000;

}


/* SHOW MENU */

.student-menu.show{

    display:flex;

    flex-direction:column;

}


/* MENU LINKS */

.student-menu a{

    color:#40E0D0;

    text-decoration:none;

    padding:15px 18px;

    font-size:16px;

    font-weight:bold;

    border-bottom:1px solid rgba(64,224,208,0.2);

    white-space:nowrap;

}

.student-menu a:hover{

    background-color:#40E0D0;

    color:#1e3a5f;

}


/* ================= DASHBOARD ================= */

.dashboard{

    width:850px;

    max-width:95%;

    margin:60px auto;

    padding:40px;

    border:2px solid #40E0D0;

    border-radius:15px;

    background-color:#080808;

    box-shadow:0 0 20px rgba(64,224,208,0.25);

    text-align:center;

}


/* HEADING */

h1{

    color:#40E0D0;

    font-size:35px;

    margin-bottom:15px;

}

h2{

    color:#40E0D0;

    margin-bottom:25px;

}


/* STUDENT INFORMATION */

.info{

    font-size:18px;

    color:white;

    margin:12px 0;

}


/* ================= BUTTONS ================= */

.menu-buttons{

    display:flex;

    justify-content:center;

    gap:15px;

    flex-wrap:wrap;

    margin-top:35px;

}

.menu-buttons a{

    padding:12px 20px;

    border:2px solid #40E0D0;

    border-radius:7px;

    color:#40E0D0;

    text-decoration:none;

    font-weight:bold;

    transition:0.2s;

}

.menu-buttons a:hover{

    background-color:#40E0D0;

    color:black;

}


/* ================= LOGOUT ================= */

.logout{

    margin-top:30px;

    display:inline-block;

    padding:12px 25px;

    background-color:#40E0D0;

    color:black;

    text-decoration:none;

    font-weight:bold;

    border-radius:7px;

}

.logout:hover{

    background-color:black;

    color:#40E0D0;

    border:2px solid #40E0D0;

}


/* ================= MOBILE ================= */

@media(max-width:700px){

    body{

        padding:90px 10px 30px;

    }

    nav{

        padding:10px;

        gap:5px;

    }

    nav a{

        font-size:10px;

    }

    nav p{

        font-size:14px;

    }

    .dashboard{

        padding:25px 15px;

    }

    h1{

        font-size:28px;

    }

    .info{

        font-size:16px;

    }

}

</style>

</head>


<body>


<!-- ================= NAVIGATION ================= -->

<nav>


<p>|</p>

<a href="index.html">
HOME
</a>


<p>|</p>

<a href="information.html">
INFO
</a>


<p>|</p>

<a href="courses.html">
COURSES
</a>


<p>|</p>

<a href="login.html">
LOGIN
</a>


<p>|</p>

<a href="forms.html">
REGISTRATION
</a>


<p>|</p>

<a href="contact.html">
📞 CONTACT US
</a>


<!-- ================= STUDENT ICON ================= -->

<div class="student-dashboard">


<button
    class="student-logo"
    type="button"
    onclick="toggleStudentMenu()"
    title="Student Dashboard">

🎓

</button>


<!-- ================= STUDENT MENU ================= -->

<div
    class="student-menu"
    id="studentMenu">


<a href="student-dashboard.php">

🏠 Dashboard

</a>


<!-- IMPORTANT: YOUR FILE IS profile.html -->

<a href="profile.html">

👤 My Profile

</a>


<a href="my-courses.php">

📚 My Courses

</a>


<!-- CERTIFICATE LIST -->

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


<!-- ================= MAIN DASHBOARD ================= -->

<div class="dashboard">


<h1>

🎓 Welcome,

<?php

echo htmlspecialchars($firstname);

?>

!

</h1>


<h2>

Student Dashboard

</h2>


<p class="info">

<strong>Name:</strong>

<?php

echo htmlspecialchars(
    $firstname . " " . $lastname
);

?>

</p>


<p class="info">

<strong>Email:</strong>

<?php

echo htmlspecialchars($email);

?>

</p>


<!-- ================= DASHBOARD BUTTONS ================= -->

<div class="menu-buttons">


<!-- PROFILE -->

<a href="profile.html">

👤 My Profile

</a>


<!-- COURSES -->

<a href="my-courses.php">

📚 My Courses

</a>


<!-- CERTIFICATES -->

<a href="my-certificate.php">

🏆 My Certificate

</a>


<!-- RESULTS -->

<a href="results.php">

📊 My Results

</a>


</div>


<!-- LOGOUT -->

<a
    href="logout.php"
    class="logout">

🚪 Logout

</a>


</div>


<!-- ================= JAVASCRIPT ================= -->

<script>

function toggleStudentMenu(){

    const menu =
        document.getElementById("studentMenu");

    menu.classList.toggle("show");

}


/* Close menu when clicking outside */

document.addEventListener("click", function(event){

    const dashboard =
        document.querySelector(".student-dashboard");

    const menu =
        document.getElementById("studentMenu");


    if(
        !dashboard.contains(event.target)
    ){

        menu.classList.remove("show");

    }

});

</script>


</body>

</html>