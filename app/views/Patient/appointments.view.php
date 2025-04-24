<?php
//print_r($appointments);
?>

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
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="header">
                    <button class="btn" onclick="window.location.href='doc_appointment'">Schedule an Appointment</button>
                    <span>
                        <button class="btn1">Cancellation Policy</button>
                    </span>
                </div>
                <hr>
                <div class="filters">
                    <label for="paymentFilter">Payment Status:</label>
                    <select id="paymentFilter" onchange="filterAppointments()">
                        <option value="all">All</option>
                        <option value="Paid">Paid</option>
                        <option value="Payment Pending">Payment Pending</option>
                    </select>

                    <label for="presenceFilter">Presence Status:</label>
                    <select id="presenceFilter" onchange="filterAppointments()">
                        <option value="all">All</option>
                        <option value="Present">Present</option>
                        <option value="Not Present">Not Present</option>
                    </select>
                    <button type="button" class="refresh-btn"
                    onclick="window.location.href = window.location.href;">Refresh</button>
                </div>

                <div class="container">
                    <?php if (!empty($appointments)) : ?>
                        <?php foreach ($appointments as $appointment) : ?>
                            <?php
                            $statusRaw = strtolower(str_replace(' ', '', (string)($appointment->state ?? '')));
                            $presenceStatus = ($statusRaw === 'present') ? 'Present' : 'Not Present';
                            $rawStatus = strtolower(str_replace(' ', '', (string)($appointment->payment_status ?? '')));
                            $paymentStatus = ($rawStatus === 'paid') ? 'Paid' : 'Payment Pending';

                            if ($paymentStatus === 'Paid') {
                                $color = 'green';
                                $label = 'Paid';
                            } else {
                                $color = 'orange';
                                $label = 'Payment Pending';
                            }
                            ?>

                            <div class="card"
                                data-payment="<?= $paymentStatus ?>"
                                data-presence="<?= $presenceStatus ?>">

                                <p>Hi <span><?= htmlspecialchars((string)($_SESSION['USER']->first_name ?? 'Patient')) ?></span>,</p>
                                <p>You have an appointment with:</p>
                                <p class="doc_name">
                                    <span>Dr. <?= htmlspecialchars((string)($appointment->doctor_first_name ?? '')) . ' ' . htmlspecialchars((string)($appointment->doctor_last_name ?? '')) ?>
                                        (<?= htmlspecialchars((string)($appointment->specialization ?? '')) ?>)</span>
                                </p>
                                <p>Appointment Number: <span><strong><?= htmlspecialchars((string)($appointment->appointment_id ?? '')) ?></strong></span></p>
                                <p>Appointment Date: <span><strong><?= htmlspecialchars((string)(date('Y-m-d', strtotime($appointment->date ?? 'now')))) ?></strong></span></p>
                                <p>Appointment Time: <span><strong><?= htmlspecialchars((string)($appointment->start_time ?? '')) ?></strong></span></p>

                                <p>Appointment Status: <span style="font-weight:bold;"><?= $presenceStatus ?></span></p>
                                <p>
                                    Payment Status:
                                    <span style="color: <?= $color ?>; font-weight: bold; padding: 4px 8px; border-radius: 4px;">
                                        <?= $label ?>
                                    </span>
                                </p>

                                <div class="buttons">
                                    <button class="cancel" onclick="showModal(<?= $appointment->id ?>)">Cancel</button>
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
        function showModal(appointmentId) {
            appointmentIdToCancel = appointmentId;
            document.getElementById("cancelModal").style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("cancelModal").style.display = "none";
        }

        // Function to handle cancellation
        function cancelAppointment() {
            //alert("Appointment Cancelled."); // Replace this with actual cancellation logic
            if (appointmentIdToCancel) {
                // Redirect to refund route with appointment ID
                window.location.href = `http://localhost/wellbe/public/patient/refund/${appointmentIdToCancel}`;
            }
            closeModal();
            alert("Appointment Cancelled.");
        }

        // Close the modal if clicked outside of it
        window.onclick = function(event) {
            var modal = document.getElementById("cancelModal");
            if (event.target == modal) {
                closeModal();
            }
        }

        function filterAppointments() {
            const paymentFilter = document.getElementById('paymentFilter').value.toLowerCase().trim();
            const presenceFilter = document.getElementById('presenceFilter').value.toLowerCase().trim();

            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                const payment = card.getAttribute('data-payment').toLowerCase().trim();
                const presence = card.getAttribute('data-presence').toLowerCase().trim();

                const matchesPayment = (paymentFilter === 'all' || payment === paymentFilter);
                const matchesPresence = (presenceFilter === 'all' || presence === presenceFilter);

                if (matchesPayment && matchesPresence) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>