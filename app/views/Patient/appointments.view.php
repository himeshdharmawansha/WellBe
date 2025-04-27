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
                        <button class="btn1" onclick="showPolicyModal()">Cancellation Policy</button>
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
                        <option value="Done">Done</option>
                    </select>
                    <button type="button" class="refresh-btn"
                    onclick="window.location.href = window.location.href;">Refresh</button>
                </div>

                <div class="container">
                    <?php if (!empty($appointments)) : ?>
                        <?php foreach ($appointments as $appointment) : ?>
                            <?php
                            $statusRaw = strtolower(str_replace(' ', '', (string)($appointment->state ?? '')));
                            $presenceStatus = ($statusRaw === 'present') ? 'Present' : (($statusRaw === 'done') ? 'Done' : 'Not Present');
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
                <button class="yes-btn" onclick="cancelAppointment() ">Yes</button>
                <button class="no-btn" onclick="closeModal()">No</button>
            </div>
        </div>
    </div>

    <div id="policyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">×</span>
        <h2>What happens when you cancel your appointment?</h2>
        <p>
            When you cancel your appointment, it will be immediately canceled, and the payment will be refunded to your e-wallet. This means that the money you paid for the appointment will be available in your e-wallet for future use. You can then use the balance in your e-wallet to pay for future appointments or other services.
        </p>
    </div>
</div>



    <!-- JavaScript for Modal -->
    <script>
        // Function to show the modal
// Store the appointment ID to be canceled
let appointmentIdToCancel = null;

// Function to show the cancel modal
function showModal(appointmentId) {
    appointmentIdToCancel = appointmentId; // Store the ID of the appointment
    document.getElementById("cancelModal").style.display = "block"; // Show cancel modal
}

// Function to close the modal (for both cancel and policy modals)
function closeModal() {
    document.getElementById("cancelModal").style.display = "none"; // Close the cancel modal
    document.getElementById("policyModal").style.display = "none"; // Close the policy modal as well
}

// Function to show the policy modal (this will be triggered after the appointment is canceled)
function showPolicyModal() {
    document.getElementById("policyModal").style.display = "block"; // Show the policy modal
}

// Function to handle appointment cancellation
function cancelAppointment() {
    if (appointmentIdToCancel) {
        // Redirect to the cancellation/refund route with appointment ID
        window.location.href = `http://localhost/wellbe/public/patient/refund/${appointmentIdToCancel}`;
        // After the redirection, show the policy modal
        showPolicyModal(); // Show the cancellation policy modal after the appointment is canceled
    }
    closeModal(); // Close the cancel modal
}

// Close the modal if clicked outside of it (for both modals)
window.onclick = function(event) {
    var cancelModal = document.getElementById("cancelModal");
    var policyModal = document.getElementById("policyModal");

    // If the user clicks outside of the cancel modal or policy modal, close them
    if (event.target === cancelModal || event.target === policyModal) {
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