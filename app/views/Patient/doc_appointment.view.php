<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/doc_appointment.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
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
            include $_SERVER['DOCUMENT_ROOT'] . '/WellBe/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- <div class="welcome-message">
                    <p>Welcome Mr. K.S. Perera</p>
                </div> -->

                <div class="flex-container">
    <!-- Doctor Search Section -->
    <div class="form-section">
        <div class="header">
            <p class="header-title">Search Your Doctor</p>
        </div>
        <form method="POST" action="">
            <!-- Doctor Selection -->
            <div class="input-box">
                <label for="doctor">Select Doctor</label>
                <input list="doctors" id="doctor" name="doctor" placeholder="Type doctor name">
                <datalist id="doctors">
                    <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= htmlspecialchars($doctor->name) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <!-- Specialization Selection -->
            <div class="input-box">
                <label for="specialization">Specialization</label>
                <input list="specializations" id="specialization" name="specialization"
                    placeholder="Type specialization">
                <datalist id="specializations">
                    <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= htmlspecialchars($doctor->specialization); ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <button type="button" class="find-doctor-btn" id="searchDoctorBtn">Search</button>
            </form>
    </div>

    <!-- Date & Time Selection Section -->
    <div id="selectionSection" class="selection-section" style="display: none;">
    <!-- Available Dates -->
        <div class="input-box">
            <label for="dates">Available Dates</label>
            <div id="dates-container" class="dates-grid">
              
            

            <?php if (!empty($data['dates'])): ?>
    <?php foreach ($data['dates'] as $day): ?>
        <?php if ($day['appointment_id'] <= 15): ?>
            <button class="date-btn">
                <div><?= htmlspecialchars($day['day']) ?></div>
                <div>App. Number: <?= htmlspecialchars($day['appointment_id']) ?></div>
                <div><?= htmlspecialchars($day['start_time']) ?></div>
            </button>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>No available dates.</p>
<?php endif; ?>


            </div>
        </div>

        <button class="find-doctor-btn" onclick="window.location.href='hello'">Confirm</button>

    </div>
</div>

            </div>
        </div>
    </div>

    <div id="customAlert" class="modal">
    <div class="modal-content">
        <p>Please select a doctor and specialization before proceeding.</p>
        <button id="closeAlert">OK</button>
    </div>
</div>


    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchButton = document.getElementById("searchDoctorBtn");
        const customAlert = document.getElementById("customAlert");
        const closeAlert = document.getElementById("closeAlert");
        const selectionSection = document.getElementById("selectionSection");
        const dateButtons = document.querySelectorAll(".date-btn");
        const timeslotButtons = document.querySelectorAll(".timeslot-btn");
        const docName = document.getElementById("doctor");
        const specialization = document.getElementById("specialization");

        // Ensure selection section starts hidden
        selectionSection.style.display = "none";
        selectionSection.style.opacity = "0";
        selectionSection.style.transition = "opacity 0.5s ease-in-out";

        // Show selection section when search button is clicked (only if inputs are filled)
        searchButton.addEventListener("click", () => {
    if (docName.value.trim() === "" || specialization.value.trim() === "") {
        customAlert.style.display = "flex"; // Show modal 
        return;
    }

    selectionSection.style.display = "block";
    setTimeout(() => {
        selectionSection.style.opacity = "1";
    }, 10);

    console.log(`Selected Doctor: ${docName.value}`);
    console.log(`Selected Specialization: ${specialization.value}`);
});

// Close modal when clicking "OK"
closeAlert.addEventListener("click", () => {
    customAlert.style.display = "none";
});

        // Handle date selection
        dateButtons.forEach(button => {
            button.addEventListener("click", () => {
                dateButtons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");
                console.log(`Selected date: ${button.textContent}`);
            });
        });

        // Handle time slot selection
        timeslotButtons.forEach(button => {
            button.addEventListener("click", () => {
                timeslotButtons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");
                console.log(`Selected time slot: ${button.textContent}`);
            });
        });
    });
</script>


</body>

</html>
