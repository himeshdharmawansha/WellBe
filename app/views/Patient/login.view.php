<?php
session_start(); // Start session
include('dbconnection.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['errors'] = []; // Clear previous errors

    // Retrieve and sanitize user input
    $nic = trim($_POST['nic']);
    $password = trim($_POST['password']);

    // Determine table to query based on user type
    $user_type = $_SESSION['user_type'] ?? ''; // Ensure this is set elsewhere in your app

    if (empty($nic)) {
        $_SESSION['errors']['nic'] = 'NIC is required';
    }
    if (empty($password)) {
        $_SESSION['errors']['password'] = 'Password is required';
    }

    if (empty($_SESSION['errors'])) {
        $query = "SELECT * FROM $user_type WHERE nic = ?";
        $stmt = $con->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $nic);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                // Verify password
                if ($row['password'] === $password) {
                    $_SESSION['USER'] = $row; // Save user details in the session

                    // Redirect based on user type
                    if ($user_type == "patient") {
                        header("Location: http://localhost/Appointment/patient_dashboard.php?nic=$nic");
                    } elseif ($user_type == "doctor") {
                        header("Location: http://localhost/Appointment/doctor_dashboard.php?nic=$nic");
                    }
                    
                    exit;
                } else {
                    $_SESSION['errors']['password'] = 'Incorrect password';
                }
            } else {
                $_SESSION['errors']['nic'] = 'NIC not found';
            }
            $stmt->close();
        } else {
            $_SESSION['errors']['database'] = 'Database query error';
        }
    }
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="./login.css">
    <title>Login</title>
</head>

<body>
    <div class="loginsignup">
        <div class="loginsignup-container">
            <div class="logo-container">
            <img class="logo" src="./assets/logo.png" />
                <div class="logo_text">WELL BE</div>
            </div>
            <h1>Log In</h1>
            <form method="post">
                <div class="loginsignup-fields">
                    <input name="nic" type="text" placeholder="Type your NIC number" />
                    <input name="password" type="password" placeholder="Type your Password" />
                </div>

                <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['errors'])): ?>
                    <div class="error-messages">
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <p class="error"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <button>LOGIN</button>
            </form>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === "patient"): ?>

                <p class="loginsignup-login">
                    Create an account <span><a href="./form1.php">Click here</a></span>
                </p>
            <?php endif; ?>




            <div class='loginsignup-agree'>
                <input type='checkbox' name='' id='' />
                <p>By continuing, I agree to the terms of use & privacy policy.</p>
            </div>
        </div>

    </div>
</body>

</html>