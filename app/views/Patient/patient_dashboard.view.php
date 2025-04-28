<?php
// Remove print_r for production
// print_r($rescheduledAppointments);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Patient Dashboard</title>
  <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/patient_dashboard.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
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
      $pageTitle = "Dashboard";
      include $_SERVER['DOCUMENT_ROOT'] . '/WellBe/app/views/Components/header.php';
      ?>

      <!-- Dashboard Content -->
      <div class="container">
        <div class="dashboard">
          <div class="profile-card">

            <div class="image">
              <?php
              $profileImage = ($_SESSION['USER']->gender == 'M') ? 'male_pro.png' : 'female_pro.png';
              ?>
              <img src="<?= ROOT ?>/assets/images/<?= $profileImage ?>" alt="Profile Picture" class="profile-img" />
            </div>
            <div class="text-data">
              <span class="name" style="font-size: 25px;"><strong> <?= $_SESSION['USER']->first_name; ?> <?= $_SESSION['USER']->last_name; ?></strong></span>
            </div>
            <br>
            <div class="profile-details">
              <p><strong>Gender: </strong> <?= $_SESSION['USER']->gender; ?></p>
              <p><strong>Contact: </strong> <?= $_SESSION['USER']->contact; ?></p>
              <p><strong>Emergency Contact: </strong><?= $_SESSION['USER']->emergency_contact_no; ?></p>
              <p><strong>Email: </strong> <?= $_SESSION['USER']->email; ?></p>
              <p><strong>Address: </strong> <?= $_SESSION['USER']->address; ?></p>
              <br>
              <div class="medical-info">
                <p><strong>Medical History: <?= $_SESSION['USER']->medical_history; ?></strong> </p>
                <p><strong>Allergies: <?= $_SESSION['USER']->allergies; ?></strong></p>
              </div>
             
              <div style="background-color: #fff3cd; color: #856404; padding: 10px 20px; border: 1px solid #ffeeba; border-radius: 5px; display: inline-block; margin-top: 10px;">

                <div class="tooltip-container">
                  <span>Your E-Wallet Balance:</span>
                  <div class="tooltip-text">E-Wallet is your digital balance used for paying doctor appointment fees.</div>
                </div>
                <strong>Rs. <?= htmlspecialchars($ewalletAmount->e_wallet ?? 0) ?></strong>
              </div>

            </div>
            <div class="buttons">
              <button class="button" onclick="window.location.href='chat'">Message</button>
              <button class="button" onclick="window.location.href='<?= ROOT ?>/patient/edit_profile'">Edit Profile</button>
            </div>

          </div>

          <div class="right">
            <div class="cards-container">
              <div class="card med-rep">
                <div class="circle-background">
                  <i class="fas fa-notes-medical icon"></i>
                </div>
                <div class="label" onclick="window.location.href='medicalreports'">View Medical Reports</div>
              </div>
              <div class="card lab-rep">
                <div class="circle-background">
                  <i class="fas fa-flask icon"></i>
                </div>
                <div class="label" onclick="window.location.href='labreports'">View Lab Reports</div>
              </div>
              <div class="card app">
                <div class="circle-background">
                  <i class="fas fa-user-md icon"></i>
                </div>
                <div class="label" onclick="window.location.href='doc_appointment'">Book an Appointment</div>
              </div>
            </div>

            <div class="calendar-wrapper">
              <div class="calendar-container">
                <h3>BMI Calculator</h3>
                <form id="bmiForm" class="bmi-form">
                  <label for="height">Height (cm):</label>
                  <input type="number" id="height" placeholder="Enter height in cm" required />
                  <label for="weight">Weight (kg):</label>
                  <input type="number" id="weight" placeholder="Enter weight in kg" required />
                  <button type="submit" class="submit-btn">Calculate BMI</button>
                  <button type="button" id="refreshBtn" class="refresh-btn">Refresh</button>
                </form>
                <div id="bmiResult" class="bmi-result hidden">
                  <p><strong>BMI:</strong> <span id="bmiValue"></span></p>
                  <p id="bmiCategory"></p>
                </div>
              </div>

              <div class="additional-container">
                <?php if (!empty($appointments)) : ?>
                  <h3>Upcoming Appointments</h3>
                  <div class="mini-scroll-container">
                    <?php foreach ($appointments as $appt) : ?>
                      <?php
                      $appointmentDateTime = strtotime($appt->date . ' ' . $appt->start_time);
                      $currentDateTime = time(); // Get the current timestamp

                      // Check if the appointment has already passed
                      if ($appointmentDateTime < $currentDateTime) {
                        continue; // Skip the rendering of this card if the appointment has passed
                      }

                      ?>
                      <div class="mini-wrapper">
                        <div class="mini" onclick="window.location.href='appointments'">
                          <div class="mini-part part1">
                            <h4><?= htmlspecialchars($appt->doctor_first_name . " " . $appt->doctor_last_name) ?>
                              (<?= htmlspecialchars($appt->specialization) ?>)</h4>
                          </div>
                          <div class="mini-part part2">
                            <span>Date: <span><?= date('Y-m-d', strtotime($appt->date)) ?></span></span>
                          </div>
                          <div class="mini-part part3">
                            <span>Appointment Time: <span><?= htmlspecialchars($appt->start_time) ?></span></span>
                          </div>
                          <div class="mini-part part3">
                            <span>Appointment No: <span><?= htmlspecialchars($appt->appointment_id) ?></span></span>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else : ?>
                  <p>No upcoming appointments.</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="popupModal2" class="modal hidden">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Thank You!</h2>
          </div>
          <div class="modal-body">
            <p>Your Payment has been made successfully. Thanks!</p>
          </div>
          <div class="modal-footer">
            <button id="closeModal2" class="submit-btn">OK</button>
          </div>
        </div>
      </div>

      <?php if (!empty($rescheduledAppointments)) :
        $last = end($rescheduledAppointments);
      ?>
        <div id="reschedulePopup" class="reschedule-modal modal-hidden">
          <div class="reschedule-modal-content">
            <div class="reschedule-modal-header">
              <h2>Rescheduled Appointment</h2>
            </div>
            <div class="reschedule-modal-body">
              <p style="color:black">
                Your appointment with Dr.<?= htmlspecialchars($last->doctor_name) ?> (<?= htmlspecialchars($last->specialization) ?>)
                on <?= date('Y-m-d', strtotime($last->date)) ?> has been rescheduled.
              </p>
            </div>
            <div class="reschedule-modal-footer">
              <button id="manageBtn" class="submit-btn" style="background-color: blue;">Manage Appointment</button>
            </div>
          </div>
        </div>
        <div id="managePopup" class="manage-modal modal-hidden">
          <div class="manage-modal-content">
            <div class="manage-modal-header">
              <h2>Manage Appointment</h2>
            </div>
            <div class="manage-modal-body">
              <p style="color: black;">Would you like to reschedule or cancel your appointment?</p>
            </div>
            <div class="manage-modal-footer">
              <button style="margin-bottom: 10px;" class="submit-btn" onclick="window.location.href = 'http://localhost/wellbe/public/patient/reschedule_doc_appointment/<?= $last->id ?>'">
                Reschedule Appointment
              </button>
              <button style="background-color: red;" class="submit-btn" onclick="window.location.href='http://localhost/wellbe/public/patient/refund/<?= $last->id ?>'">
                Cancel Appointment
              </button>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <script src="./script.js"></script>
      <script>
        function getQueryParam(param) {
          const urlParams = new URLSearchParams(window.location.search);
          const value = urlParams.get(param);
          console.log(`Query Parameter "${param}" Value:`, value);
          return value;
        }
        document.addEventListener("DOMContentLoaded", () => {
          const paymentStatus = getQueryParam("payment");
          console.log("Payment Status:", paymentStatus);
          if (paymentStatus === "success") {
            const modal = document.getElementById("popupModal2");
            console.log("Displaying Modal");
            modal.classList.remove("hidden");
            document.getElementById("closeModal2").addEventListener("click", () => {
              modal.classList.add("hidden");
            });
          }
        });
      </script>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          console.log("DOM Content Loaded");
          const reschedulePopup = document.getElementById("reschedulePopup");
          const managePopup = document.getElementById("managePopup");

          console.log("reschedulePopup:", reschedulePopup);
          console.log("managePopup:", managePopup);

          <?php if (!empty($rescheduledAppointments)) : ?>
            if (reschedulePopup) {
              console.log("Removing modal-hidden class from reschedulePopup");
              reschedulePopup.classList.remove("modal-hidden");
              console.log("reschedulePopup classes:", reschedulePopup.className);
            } else {
              console.error("reschedulePopup not found in DOM");
            }

            const manageBtn = document.getElementById("manageBtn");
            if (manageBtn) {
              console.log("Adding click event to manageBtn");
              manageBtn.addEventListener("click", () => {
                console.log("Manage button clicked");
                reschedulePopup.classList.add("modal-hidden");
                managePopup.classList.remove("modal-hidden");
              });
            } else {
              console.error("manageBtn not found in DOM");
            }
          <?php endif; ?>
        });
      </script>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const bmiForm = document.getElementById("bmiForm");
          const bmiResult = document.getElementById("bmiResult");
          const bmiValue = document.getElementById("bmiValue");
          const bmiCategory = document.getElementById("bmiCategory");

          bmiForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const height = parseFloat(document.getElementById("height").value);
            const weight = parseFloat(document.getElementById("weight").value);
            if (height > 0 && weight > 0) {
              const bmi = (weight / ((height / 100) ** 2)).toFixed(2);
              bmiValue.textContent = bmi;
              let category = "";
              if (bmi < 18.5) {
                category = "Underweight";
              } else if (bmi >= 18.5 && bmi < 24.9) {
                category = "Normal weight";
              } else if (bmi >= 25 && bmi < 29.9) {
                category = "Overweight";
              } else {
                category = "Obese";
              }
              bmiCategory.textContent = `Category: ${category}`;
              bmiResult.classList.remove("hidden");
            } else {
              alert("Please enter valid height and weight values.");
            }
          });

          document.getElementById("refreshBtn").addEventListener("click", () => {
            document.getElementById("height").value = "";
            document.getElementById("weight").value = "";
            bmiResult.classList.add("hidden");
          });
        });
      </script>
</body>

</html>