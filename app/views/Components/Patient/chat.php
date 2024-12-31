<?php
session_start();

require 'dbconnection.php';

if (!isset($_GET['nic']) || empty($_GET['nic'])) {
  die("Invalid access");
}

$nic = htmlspecialchars($_GET['nic']);

if (!isset($_GET['nic']) || empty($_GET['nic'])) {
  die("Invalid NIC provided");
}

$nic = htmlspecialchars($_GET['nic']); // Sanitize input

// Prepare and execute the query
$stmt = $con->prepare("SELECT * FROM patient WHERE nic = ?");
$stmt->bind_param("s", $nic); // Bind NIC as a string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $patient = $result->fetch_assoc(); // Fetch patient data
} else {
  die("No patient found with NIC: " . htmlspecialchars($nic));
}

// Close resources
$stmt->close();
$con->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Message</title>
   <link rel="stylesheet" href="./chat.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
     <!-- Sidebar -->
     <div class="sidebar">
      <div class="sidebar-logo">
        <img src=" logo.png">
        <h2>WELLBE</h2>
      </div>
      <ul class="sidebar-menu">
        <li class="active">
          <a href="patient_dashboard.php?nic=<?= urlencode($nic) ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-text">Dashboard</span>
          </a>
        </li>
        <li>
        <a href="medicalreports.php?nic=<?= urlencode($nic) ?>">
            <i class="fas fa-notes-medical"></i>
            <span class="menu-text">View Medical Reports</span>
          </a>
        </li>
        <li>
        <a href="labreports.php?nic=<?= urlencode($nic) ?>">
            <i class="fas fa-flask"></i>
            <span class="menu-text">View Lab Reports</span>
          </a>
        </li>
        <li>
          <a href="doc_appointment.php?nic=<?= urlencode($nic) ?>">
            <i class="fas fa-user-md"></i>
            <span class="menu-text">Search for a Doctor</span>
          </a>

        </li>
        <li>
        <a href="appointments.php?nic=<?= urlencode($nic) ?>">
            <i class="fas fa-calendar-alt"></i>
            <span class="menu-text">Appointments</span>
          </a>

        </li>
        <li>
        <a href="chat.php?nic=<?= urlencode($nic) ?>">
            <i class="fas fa-comments"></i>
            <span class="menu-text">Chat with Doctor</span>
          </a>
        </li>
        
        <li>
          <i class="fas fa-sign-out-alt"></i><span class="menu-text" onclick="window.location.href='logout.php'">Logout</span>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Header -->
      <header class="main-header">
        <div class="header-left"><h1>Chat with Doctor</h1></div>
        <div class="header-right">
          <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge"></span>
          </div>
          <div class="user-details">
            <div class="user-info">
              <p class="name"><?= htmlspecialchars($patient['first_name']) ?>
                <?= htmlspecialchars($patient['last_name']) ?>
              </p>
              <p class="role">Patient</p>
            </div>
          </div>
        </div>
      </header>

         <!-- Dashboard Content -->
         <div class="dashboard-content">
            <div class="container">
               <div class="chat-list">
                  <div class="search-bar">
                     <input type="text" placeholder="Search">
                  </div>
                  <ul>
                     <li>
                        <div class="chat-item">
                           <div class="avatar"></div>
                           <div class="chat-info">
                              <h4>Kumari Siriwardana</h4>
                              <p>Sent attachment</p>
                           </div>
                           <span class="time">9:00am</span>
                        </div>
                     </li>
                     <li>
                        <div class="chat-item">
                           <div class="avatar"></div>
                           <div class="chat-info">
                              <h4>Rumesh Kannangara</h4>
                              <p>Sent attachment</p>
                           </div>
                           <span class="time">9:00am</span>
                        </div>
                     </li>
                  </ul>
               </div>

               <div class="chat-window">
                  <div class="chat-header">
                     <div class="avatar"></div>
                     <div class="header-info">
                        <h4>Kumari Siriwardana</h4>
                        <p>Online</p>
                     </div>
                  </div>
                  <div class="chat-messages">
                     <div class="message received">
                        <p>ullamco veniam, quis nostrud exer labor...</p>
                        <span class="time">11:20pm</span>
                     </div>
                     <div class="message sent">
                        <p>Lorem ipsum dolor sit amet, consectetu...</p>
                        <span class="time">11:25am</span>
                     </div>
                     <div class="message received">
                        <p>ullamco veniam, quis nostrud exer labor...</p>
                        <span class="time">11:26pm</span>
                     </div>
                     <div class="message sent">
                        <p>ullamco veniam, quis nostrud exer labor...</p>
                        <span class="time">11:25am</span>
                     </div>
                  </div>
                  <div class="chat-input">
                     <input type="text" placeholder="Type a message">
                     <button>Send</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script src="../Message/message.js"></script>
</body>

</html>