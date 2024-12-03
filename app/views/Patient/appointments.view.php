<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/Appointment.css">
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
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="header">
                    <p>Appointments
                        <button class="btn" onclick="window.location.href='search_for_doctor'">Schedule an Appointment</button>
                        <span>
                            <button class="btn1">Reschedule/ Cancellation Policy</button>
                        </span>
                    </p>
                </div>
                <hr>  
                <div class="container">
                    <div class="card">
                        <p>Hi K.S.Perera,</p>
                        <p>you have an appointment<br>with</p>
                        <p class="doc_name">Dr. Narayanan (Cardiologist)<br><p>on</p></p>
                        <h1>25</h1>
                        <h2>Monday<br>September 2024</h2>
                        <div class="buttons">
                            <button class="accept">Details</button>
                            <button class="reschedule">Reschedule</button>
                            <button class="cancel" onclick="showModal()">Cancel</button>
                        </div>
                    </div>
                    <div class="card">
                        <p>Hi K.S.Perera,</p>
                        <p>you have an appointment<br>with</p>
                        <p class="doc_name">Dr. Narayanan (Cardiologist)<br><p>on</p></p>
                        <h1>25</h1>
                        <h2>Monday<br>September 2024</h2>
                        <div class="buttons">
                            <button class="accept">Details</button>
                            <button class="reschedule">Reschedule</button>
                            <button class="cancel" onclick="showModal()">Cancel</button>
                        </div>
                    </div>
                    <div class="card">
                        <p>Hi K.S.Perera,</p>
                        <p>you have an appointment<br>with</p>
                        <p class="doc_name">Dr. Narayanan (Cardiologist)<br><p>on</p></p>
                        <h1>25</h1>
                        <h2>Monday<br>September 2024</h2>
                        <div class="buttons">
                            <button class="accept">Details</button>
                            <button class="reschedule">Reschedule</button>
                            <button class="cancel" onclick="showModal()">Cancel</button>
                        </div>
                    </div>
                    <div class="card">
                        <p>Hi K.S.Perera,</p>
                        <p>you have an appointment<br>with</p>
                        <p class="doc_name">Dr. Narayanan (Cardiologist)<br><p>on</p></p>
                        <h1>25</h1>
                        <h2>Monday<br>September 2024</h2>
                        <div class="buttons">
                            <button class="accept">Details</button>
                            <button class="reschedule">Reschedule</button>
                            <button class="cancel" onclick="showModal()">Cancel</button>
                        </div>
                    </div>
                    

                    <!-- Additional appointment cards here -->

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
