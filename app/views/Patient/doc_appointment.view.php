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

            <button class="find-doctor-btn" onclick="window.location.href='hello'">Search</button>
        </form>
    </div>

    <!-- Date & Time Selection Section -->
    <div class="selection-section">
        <!-- Available Dates -->
        <div class="input-box">
            <label for="dates">Available Dates</label>
            <div id="dates-container" class="dates-grid">
                <?php foreach($data['dates'] as $day){ ?>
                    <?php if($day['appointment_id']<=15) {?>
                        <button class="date-btn"><div><?= $day['day'] ?></div><div>App.Number : <?= $day['appointment_id'] ?></div>
                        <div><?= $day['start_time'] ?></div></button>
                    <?php }?>
                <?php } ?>
            </div>
        </div>

        <!-- Available Time Slots -->
        <div class="input-box">
            <label for="timeslots">Available Time Slots</label>
            <div id="timeslots-container" class="timeslots-grid">
                <button class="timeslot-btn">9:00 AM</button>
                <button class="timeslot-btn">10:30 AM</button>
                <button class="timeslot-btn">12:00 PM</button>
                <button class="timeslot-btn">2:00 PM</button>
                <button class="timeslot-btn">3:30 PM</button>
                <button class="timeslot-btn">5:00 PM</button>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const dateButtons = document.querySelectorAll(".date-btn");
        const timeslotButtons = document.querySelectorAll(".timeslot-btn");

        // Handle date selection
        dateButtons.forEach(button => {
            button.addEventListener("click", () => {
                // Remove active state from other date buttons
                dateButtons.forEach(btn => btn.classList.remove("active"));

                // Add active state to clicked date button
                button.classList.add("active");

                // Log the selected date
                console.log(`Selected date: ${button.textContent}`);
            });
        });

        // Handle time slot selection
        timeslotButtons.forEach(button => {
            button.addEventListener("click", () => {
                // Remove active state from other time slot buttons
                timeslotButtons.forEach(btn => btn.classList.remove("active"));

                // Add active state to clicked time slot button
                button.classList.add("active");

                // Log the selected time slot
                console.log(`Selected time slot: ${button.textContent}`);
            });
        });
    });
    </script>

</body>

</html>
