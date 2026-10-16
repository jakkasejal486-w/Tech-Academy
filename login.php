  <?php

session_start();

include "db.php";


/* Check database connection */

if ($conn->connect_error) {

    die("Database connection failed: " . $conn->connect_error);

}


/* Only allow POST */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: login.html");
    exit();

}


/* Get form data */

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";


/* Check empty fields */

if ($email === "" || $password === "") {

    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Login Error</title>

        <style>

        body{
            background:black;
            color:white;
            text-align:center;
            padding-top:150px;
            font-family:Arial,sans-serif;
        }

        h2{
            color:#40E0D0;
        }

        a{
            display:inline-block;
            margin-top:20px;
            padding:12px 25px;
            background:#40E0D0;
            color:black;
            text-decoration:none;
            font-weight:bold;
            border-radius:7px;
        }

        </style>
    </head>

    <body>

        <h2>Please enter email and password.</h2>

        <a href='login.html'>Try Again</a>

    </body>
    </html>
    ";

    exit();

}


/* Find student */

$stmt = $conn->prepare(
    "SELECT id, Firstname, lastname, email, password
     FROM student
     WHERE email = ?
     LIMIT 1"
);


if (!$stmt) {

    die("SQL Prepare Error: " . $conn->error);

}


$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


/* Email found */

if ($result->num_rows === 1) {

    $student = $result->fetch_assoc();


    /* Check password */

    if (password_verify($password, $student["password"])) {


        /* Create session */

        $_SESSION["student_id"] = $student["id"];

        $_SESSION["firstname"] = $student["Firstname"];

        $_SESSION["lastname"] = $student["lastname"];

        $_SESSION["email"] = $student["email"];


        /* Make sure session is saved */

        session_write_close();


        /* Go to dashboard */

        header("Location: student-dashboard.php");

        exit();


    } else {

        echo "
        <!DOCTYPE html>
        <html>

        <head>

            <meta charset='UTF-8'>

            <title>Login Error</title>

            <style>

            body{
                background:black;
                color:white;
                text-align:center;
                padding-top:150px;
                font-family:Arial,sans-serif;
            }

            h2{
                color:#40E0D0;
            }

            a{
                display:inline-block;
                margin-top:20px;
                padding:12px 25px;
                background:#40E0D0;
                color:black;
                text-decoration:none;
                font-weight:bold;
                border-radius:7px;
            }

            </style>

        </head>

        <body>

            <h2>Incorrect Password!</h2>

            <a href='login.html'>Try Again</a>

        </body>

        </html>
        ";

    }


} else {


    /* Email not found */

    echo "
    <!DOCTYPE html>
    <html>

    <head>

        <meta charset='UTF-8'>

        <title>Login Error</title>

        <style>

        body{
            background:black;
            color:white;
            text-align:center;
            padding-top:150px;
            font-family:Arial,sans-serif;
        }

        h2{
            color:#40E0D0;
        }

        a{
            display:inline-block;
            margin-top:20px;
            padding:12px 25px;
            background:#40E0D0;
            color:black;
            text-decoration:none;
            font-weight:bold;
            border-radius:7px;
        }

        </style>

    </head>

    <body>

        <h2>No account found with this email!</h2>

        <a href='login.html'>Try Again</a>

    </body>

    </html>
    ";

}


$stmt->close();

$conn->close();

?>