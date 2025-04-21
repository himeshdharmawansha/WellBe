<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/appointment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php
        $this->renderComponent('navbar', $active);
        ?>

        <!-- Main Content -->
        <div class="main-content" >
            <!-- Top Header -->
            <?php
            $pageTitle = "Appointments";
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>
            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="header" style="padding: 10px; background-color: #f3f3f3;margin-bottom: 10px">
                <form action="" method="GET" style="display: flex; align-items: center;">
                    <label for="date-select" style="margin-right: 10px; font-weight: bold;">Select Date:</label>
                    <input type="date" id="date-select" name="selected_date" 
                        style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                    <button type="submit" style="margin-left: 10px; padding: 5px 10px; background-color: #2278d4; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Search
                    </button>
                </form>
                </div>
                <hr>  
                <div class="container">
                    <?php if (!empty($data['appointmentsOnDate'])): ?>
                        <?php foreach ($data['appointmentsOnDate'] as $appointment): ?>
                            <div class="card">
                                <p>Name: <span class="doc_name"><?php echo htmlspecialchars($appointment->first_name . ' ' . $appointment->last_name); ?></span></p>
                                <p>Appointment ID: <span class="doc_name"><?php echo $appointment->appointment_id; ?></span></p>
                                <p>Gender: <span class="doc_name"><?php echo $appointment->gender; ?></span></p>
                                <p>Date: <span class="doc_name"><?php echo htmlspecialchars($data['date']); ?></span></p>
                                <button class="new_patient" style="margin-top: 10px;font-weight:bold"><?php echo htmlspecialchars($appointment->patient_type); ?> Patient</button>
                                <?php if($appointment->patient_type == "Returning"): ?>
                                    <button class="returning_patient">
                                        <a style="color: #f3f3f3;font-weight:bold" href="<?= ROOT ?>/doctor/display_record/<?= $appointment->patient_id ?>">Patient Records</a>
                                    </button>
                                <?php endif ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:red;font-size:larger;margin-top:5%">No appointments yet for <?php echo htmlspecialchars($data['date']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Are you sure you want to cancel this appointment?</p>
            <div class="modal-buttons">
                <button class="yes-btn" onclick="cancelAppointment()">Yes</button>
                <button class="no-btn" onclick="closeModal()">No</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        // Function to show the modal
        function showModal() {
            document.getElementById("cancelModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("cancelModal").style.display = "none";
        }

        function redirectToPage() {
            window.location.href = "https://localhost/test/public/doctor/patient_details_upcoming/";
        }

        // Function to handle cancellation
        function cancelAppointment() {
            alert("Appointment Cancelled."); // Replace this with actual cancellation logic
            closeModal(); // Close the modal after the action is confirmed
        }

        // Close the modal if clicked outside of it
        window.onclick = function(event) {
            var modal = document.getElementById("cancelModal");
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
