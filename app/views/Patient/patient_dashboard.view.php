
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
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WellBe-1/app/views/Components/Patient/header.php';
            ?>

      <!-- Dashboard Content -->
      <div class="container">
        <div class="dashboard">
          <div class="profile-card">
            <div class="image">
                <img src="./assets/male_pro.png" alt="Profile Picture" class="profile-img" />
            </div>
            <div class="text-data">
              <span class="name"></span>
                
              <span class="job"><strong>Patient_id:</strong></span>
            </div>
            <br>
            <div class="profile-details">
              <p><strong>Gender:</strong> </p>
              <p><strong>Contact:</strong></p>
              <p><strong>Emergency Contact:</strong></p>
              <p><strong>Email:</strong></p>
              <p><strong>Address:</strong></p>
              <br>
              <div class="medical-info">
                <p><strong>Medical History:</strong> </p>
                <p><strong>Allergies:</strong></p>
              </div>
            </div>
            <div class="buttons">
              <button class="button" onclick="window.location.href='chat.php'">Message</button>
              <button class="button" onclick="window.location.href='edit_profile.php'">Edit Profile</button>

            </div>

          </div>

          <div class="right">

            <div class="cards-container">
              <div class="card med-rep">
                <div class="circle-background">
                  <i class="fas fa-user icon"></i>
                </div>

                <div class="label" onclick="window.location.href='medicalreports.php'">View Medical Reports</div>

              </div>

              <div class="card lab-rep">
                <div class="circle-background">
                  <i class="fas fa-user icon"></i>
                </div>

                <div class="label" onclick="window.location.href='labreports.php'">View Lab Reports</div>

              </div>

              <div class="card app">
                <div class="circle-background">
                  <i class="fas fa-flask icon"></i>
                </div>
                <div class="label" onclick="window.location.href='doc_appointment.php'">Book an Appointment</div>

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


      <script src="./script.js"></script>
      <script>
        // Function to parse query parameters
        function getQueryParam(param) {
          const urlParams = new URLSearchParams(window.location.search);
          const value = urlParams.get(param);
          console.log(`Query Parameter "${param}" Value:`, value); // Debugging output
          return value;
        }
        // Check if "payment" parameter is "success"
        document.addEventListener("DOMContentLoaded", () => {
          const paymentStatus = getQueryParam("payment"); // Get payment status
          console.log("Payment Status:", paymentStatus); // Debugging

          if (paymentStatus === "success") {
            const modal = document.getElementById("popupModal2");
            console.log("Displaying Modal"); // Debugging
            modal.classList.remove("hidden"); // Show the modal

            document.getElementById("closeModal2").addEventListener("click", () => {
              modal.classList.add("hidden"); // Hide the modal on button click
            });
          }
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

      // Determine BMI category
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

  refreshBtn.addEventListener("click", () => {
    document.getElementById("height").value = "";
    document.getElementById("weight").value = "";
    bmiResult.classList.add("hidden"); // Hide the results
  });

});

</script>

</body>

</html>