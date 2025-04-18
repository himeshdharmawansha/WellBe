<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/appointments.css?v=<?= time() ?>">
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
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="header">
                    <button class="btn" onclick="window.location.href='doc_appointment'">Schedule an Appointment</button>
                    <span>
                        <button class="btn1">Reschedule/ Cancellation Policy</button>
                    </span>
                </div>
                <hr>
                <div class="container">
                    <?php if (!empty($appointments)) : ?>
                        <?php foreach ($appointments as $appointment) : ?>
                            <div class="card">
                                <p>Hi <?= htmlspecialchars($_SESSION['USER']->first_name ?? 'Patient') ?>,</p>
                                <p>You have an appointment with:</p>
                                <p class="doc_name">
                                    Dr. <?= htmlspecialchars($appointment->doctor_first_name . " " . $appointment->doctor_last_name) ?>
                                    (<?= htmlspecialchars($appointment->specialization) ?>)
                                </p>
                                <p>Appointment Number: <?= htmlspecialchars($appointment->appointment_id) ?></p>

                                <p>Appointment Date: <?= date('Y-m-d', strtotime($appointment->date)) ?></p>
                                <div class="buttons">
                                    <button class="reschedule">Reschedule</button>
                                    <button class="cancel" onclick="showModal()">Cancel</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No appointments found.</p>
                    <?php endif; ?>
                </div>




                <!-- Additional appointment cards here -->
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