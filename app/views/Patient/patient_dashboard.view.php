<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Patient Dashboard</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/patient_dashboard.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <?php
    $this->renderComponent('navbar', $active);
    ?>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Header -->
      <?php
      $pageTitle = "Dashboard"; // Set the text you want to display
      include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Patient/header.php';
      ?>

      <!-- Dashboard Content -->
      <div class="container">
        <div class="dashboard">
          <div class="profile-card">
            <div class="image">
              <img src="profile.jpg" alt="" class="profile-img" />
            </div>
            <div class="text-data">
              <span class="name">Amrah Slamath</span>
              <span class="job">Patient_id: 11243</span>
            </div>
            <div class="profile-details">
              <p><strong>Age:</strong> 45</p>
              <p><strong>Gender:</strong> Male</p>
              <p value="<?php htmlspecialchars($q->contact) ?>"><strong>Contact:</strong></p>
              <p><strong>Emergency Contact:</strong> (123) 456-7890</p>
              <p><strong>Email:</strong> john.doe@example.com</p>
              <p><strong>Address:</strong> 123 Main Street, City, State, ZIP</p>
            </div>
            <div class="buttons">
              <button class="button" onclick="window.location.href='chat_with_the_doctor'">Message</button>
              <button class="button">Edit Profile</button>
            </div>

          </div>

          <div class="right">

            <div class="cards-container">
              <div class="card med-rep">
                <div class="circle-background">
                  <i class="fas fa-user icon"></i>
                </div>

                <div class="label" onclick="window.location.href='view_lab_reports'">View Medical Reports</div>
              </div>

              <div class="card lab-rep">
                <div class="circle-background">
                  <i class="fas fa-user icon"></i>
                </div>

                <div class="label" onclick="window.location.href='view_medical_reports'">View Medical Reports</div>
              </div>

              <div class="card app">
                <div class="circle-background">
                  <i class="fas fa-flask icon"></i>
                </div>
                <div class="label" onclick="window.location.href='appointments'">Book an Appointment</div>
              </div>
            </div>


            <div class="calendar-wrapper">
              <div class="calendar-container">
                <div class="calendar-header">
                  <h3>Calendar</h3>
                  <div class="calendar-nav">
                    <button id="prevMonth">&lt;</button>
                    <span id="monthYear"></span>
                    <button id="nextMonth">&gt;</button>
                  </div>
                </div>
                <table class="calendar-table">
                  <thead>
                    <tr>
                      <th>S</th>
                      <th>M</th>
                      <th>T</th>
                      <th>W</th>
                      <th>T</th>
                      <th>F</th>
                      <th>S</th>
                    </tr>
                  </thead>
                  <tbody id="calendar-body">
                    <!-- Calendar Dates will be generated dynamically -->
                  </tbody>
                </table>
              </div>

              <div class="additional-container">
                <h3>Upcoming Appointments</h3>
                <div class="mini-wrapper">
                  <div class="mini">
                    <div class="mini-part part1">
                      <h4>Dr. Upul Priyarathne</h4>
                    </div>
                    <div class="mini-part part2">
                      <span>Date: <span>24/11/2024</span></span>
                    </div>
                    <div class="mini-part part3">
                      <span>Appointment No: <span>25</span></span>
                    </div>
                  </div>

                  <div class="mini">
                    <div class="mini-part part1">
                      <h4>Dr. Saman Rathnayake</h4>
                    </div>
                    <div class="mini-part part2">
                      <span>Date: <span>24/11/2024</span></span>
                    </div>
                    <div class="mini-part part3">
                      <span>Appointment No: <span>25</span></span>
                    </div>
                  </div>
                  <div class="mini">
                    <div class="mini-part part1">
                      <h4>Dr. Jaya Swaminadan</h4>
                    </div>
                    <div class="mini-part part2">
                      <span>Date: <span>24/11/2024</span></span>
                    </div>
                    <div class="mini-part part3">
                      <span>Appointment No: <span>25</span></span>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </div>


          <script src="<?= ROOT ?>/assets/js/Patient/script.js"></script>
        </div>
      </div>

</body>

</html>