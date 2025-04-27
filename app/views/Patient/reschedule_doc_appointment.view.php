<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/doc_appointment.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Your existing HTML unchanged -->
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
            include $_SERVER['DOCUMENT_ROOT'] . '/Wellbe/app/views/Components/header.php';

            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="flex-container">
                    <!-- Doctor Search Section -->
                    <div class="form-section">
                        <div class="header">
                            <p class="header-title">Search Your Doctor</p>
                        </div>
                        <form id="appointment-form" method="POST" action="">
                            <!-- Doctor Selection -->
                            <div class="input-box">
                                <label for="doctor">Select Doctor</label>
                                <input list="doctors" id="doctor" name="doctor" placeholder="Type doctor name"
                                    value="<?= isset($_POST['doctor']) ? htmlspecialchars($_POST['doctor']) : '' ?>">
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
                                    placeholder="Type specialization"
                                    value="<?= isset($_POST['specialization']) ? htmlspecialchars($_POST['specialization']) : '' ?>">
                                <datalist id="specializations">
                                    <?php
                                    $uniqueSpecializations = [];
                                    foreach ($doctors as $doctor) {
                                        $spec = htmlspecialchars($doctor->specialization);
                                        if (!in_array($spec, $uniqueSpecializations)) {
                                            $uniqueSpecializations[] = $spec;
                                            echo "<option value=\"$spec\"></option>";
                                        }
                                    }
                                    ?>
                                </datalist>
                            </div>

                            <button type="submit" class="find-doctor-btn">Search</button>
                            <button type="button" class="refresh-btn"
                                onclick="window.location.href = window.location.href;">Refresh</button>
                        </form>
                    </div>

                    <!-- Date & Time Selection Section -->
                    <div class="selection-section">
                        <div class="input-box">
                            <label>Available Dates</label>
                            <div id="dates-container" class="dates-grid">
                                <?php if (!empty($data['dates']) && isset($_POST['doctor']) && isset($_POST['specialization'])): ?>
                                    <?php foreach ($data['dates'] as $day): ?>
                                        <?php if ($day['appointment_id'] <= 15): ?>
                                            <button class="date-btn"
                                                data-doc-id="<?= isset($data['docId']) ? $data['docId'] : '' ?>"
                                                data-doctor-fee="<?= isset($data['doctorFee']) ? $data['doctorFee'] : '' ?>"
                                                data-doctor="<?= htmlspecialchars($_POST['doctor']) ?>"
                                                data-specialization="<?= htmlspecialchars($_POST['specialization']) ?>"
                                                data-appointment-id="<?= $day['appointment_id'] ?>"
                                                data-start-time="<?= $day['start_time'] ?>"
                                                data-day="<?= $day['day'] ?>"
                                                onclick="storeSelection(this)">
                                                <div class="dawasa">Date : <?= $day['day'] ?></div>
                                                <div class="time">Starting Time : <?= $day['start_time'] ?></div>
                                                <div class="appnmbr">Appointment Number : <?= $day['appointment_id'] ?></div>
                                            </button>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Please select the doctor and specialization to show the available slots.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="confirmationModal2" class="modal-overlay" style="display:none;">
        <div class="modal-content2">
            <p id="confirmationMessage2"></p>
            <div class="modal-buttons2">
                <button id="confirmBtn2">Yes</button>
                <button onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <div id="successModal2" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <p>Appointment successfully rescheduled!</p>
            <div class="modal-buttons">
                <button style="background-color: green;" onclick="closeSuccessModal()">OK</button>
            </div>
        </div>
    </div>

    <form id="rescheduleForm" method="POST" action="">
        <input type="hidden" name="doc_id" id="hiddenDocId">
        <input type="hidden" name="appointment_id" id="hiddenAppointmentId">
        <input type="hidden" name="day" id="hiddenDay">
    </form>

    <script>
        console.log("Schedule Appointment script loaded - version 2025-04-20");

        const doctors = <?= json_encode($data['doctors']) ?>;
        const doctorInput = document.getElementById('doctor');
        const specializationInput = document.getElementById('specialization');
        const doctorsDatalist = document.getElementById('doctors');
        const specializationsDatalist = document.getElementById('specializations');

        // Helper to update datalist options
        function updateDatalist(datalistElement, values) {
            datalistElement.innerHTML = ''; // Clear current options
            const uniqueValues = [...new Set(values)]; // Remove duplicates using Set
            uniqueValues.forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                datalistElement.appendChild(option);
            });
        }

        // When specialization changes, update doctor suggestions
        specializationInput.addEventListener('input', function () {
            const selectedSpecialization = this.value.trim().toLowerCase();
            const filteredDoctors = doctors
                .filter(doc => doc.specialization.toLowerCase() === selectedSpecialization)
                .map(doc => doc.name);
            updateDatalist(doctorsDatalist, filteredDoctors);
        });

        // When doctor changes, update specialization suggestion
        doctorInput.addEventListener('input', function () {
            const selectedDoctor = this.value.trim().toLowerCase();
            const foundDoctor = doctors.find(doc => doc.name.toLowerCase() === selectedDoctor);
            if (foundDoctor) {
                specializationInput.value = foundDoctor.specialization;
                updateDatalist(specializationsDatalist, [foundDoctor.specialization]);
            } else {
                const allSpecs = doctors.map(doc => doc.specialization);
                updateDatalist(specializationsDatalist, allSpecs);
            }
        });

        function storeSelection(button) {
            const docName = button.dataset.doctor;
            const day = button.dataset.day;
            const appointmentId = button.dataset.appointmentId;

            // Show confirmation message
            const message = `Do you want to confirm rescheduling the appointment to <strong>${docName}</strong> on <strong>${day}</strong>, appointment number <strong>${appointmentId}</strong>?`;
            document.getElementById('confirmationMessage2').innerHTML = message;

            // Show the confirmation modal
            const modal = document.getElementById('confirmationModal2');
            modal.style.display = 'flex';

            // When user confirms
            document.getElementById('confirmBtn2').onclick = function () {
                // Store data in sessionStorage
                sessionStorage.setItem('doc_id', button.dataset.docId);
                sessionStorage.setItem('doctor_fee', button.dataset.doctorFee);
                sessionStorage.setItem('doctor', docName);
                sessionStorage.setItem('specialization', button.dataset.specialization);
                sessionStorage.setItem('appointment_id', appointmentId);
                sessionStorage.setItem('start_time', button.dataset.startTime);
                sessionStorage.setItem('day', day);

                // Update hidden form fields
                document.getElementById('hiddenDocId').value = sessionStorage.getItem('doc_id');
                document.getElementById('hiddenAppointmentId').value = sessionStorage.getItem('appointment_id');
                document.getElementById('hiddenDay').value = sessionStorage.getItem('day');

                // Hide confirmation modal
                document.getElementById('confirmationModal2').style.display = 'none';

                // Show success modal
                document.getElementById('successModal2').style.display = 'flex';
            };
        }

        function closeSuccessModal() {
            document.getElementById('successModal2').style.display = 'none';

            // Send form data via fetch instead of submitting the form
            const formData = new FormData(document.getElementById('rescheduleForm'));

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.ok) {
                    //alert('aaa')
                    // After successful POST, redirect
                    window.location.href = 'http://localhost/wellbe/public/patient/';
                } else {
                    alert('Something went wrong!');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
        }

        function closeModal() {
            document.getElementById('confirmationModal2').style.display = 'none';
        }
    </script>
</body>
</html>