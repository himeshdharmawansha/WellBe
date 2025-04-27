<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Form</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Receptionist/appointments.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php
        $this->renderComponent('navbar', $active);
        ?>

        <div class="main-content">
            <?php
            $pageTitle = "Schedule Appointments"; 
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <div class="content-container">
                <div class="flex-container">
                    <div class="form-section">
                        <div class="header">
                            <p class="header-title">Doctor Details</p>
                        </div>
                        <form id="appointment-form" method="POST" action="">
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

                            <button type="submit" class="submit-button">Search</button>
                        </form>
                    </div>

                    <div class="selection-section">
                        <div class="input-box">
                            <label>Available Dates</label>
                            <div id="dates-container" class="dates-grid">
                                <?php if (!empty($data['dates']) && isset($_POST['doctor']) && isset($_POST['specialization'])): ?>
                                    <?php foreach ($data['dates'] as $day): ?>
                                        <button class="date-btn"
                                            data-doc-id="<?= isset($data['docId']) ? $data['docId'] : '' ?>"
                                            data-doctor-fee="<?= isset($data['doctorFee']) ? $data['doctorFee'] : '' ?>"
                                            data-doctor="<?= htmlspecialchars($_POST['doctor']) ?>"
                                            data-specialization="<?= htmlspecialchars($_POST['specialization']) ?>"
                                            data-appointment-id="<?= $day['appointment_id'] ?>"
                                            data-start-time="<?= $day['start_time'] ?>"
                                            data-day="<?= $day['day'] ?>"
                                            onclick="storeSelection(this)"
                                            <?php if ($day['appointment_id'] > 15): ?> disabled <?php endif; ?>>
                                            <div class="dawasa">Date: <?= $day['day'] ?></div>
                                            <div class="time">Starting Time: <?= $day['start_time'] ?></div>
                                            <div class="appnmbr">
                                                <?php if ($day['appointment_id'] > 15): ?>
                                                    <span style="color: red;">All appointments are booked</span>
                                                <?php else: ?>
                                                    Appointment Number: <?= $day['appointment_id'] ?>
                                                <?php endif; ?>
                                            </div>
                                        </button>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Please select the doctor and specialization to show the available slots.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class = "flex-container">
                <div class="form-section">
                        <div class="header">
                            <p class="header-title">Patient Details</p>
                        </div>
                        <form id="appointment-form-patient" method="POST" action="">
                            <div class="input-box">
                                <label for="doctor">Patient NIC:</label>
                                <input type = "text" id="patient_nic" name = "patent_nic">
                                <label for="doctor">Patient Name:</label>
                                <input type = "text" id="patient_name" name = "patent_name">
                            </div>
                            <button type="submit" class="submit-button">Make Appointment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    const doctors = <?= json_encode($data['doctors']) ?>;

    const doctorInput = document.getElementById('doctor');
    const specializationInput = document.getElementById('specialization');
    const doctorsDatalist = document.getElementById('doctors');
    const specializationsDatalist = document.getElementById('specializations');

    function updateDatalist(datalistElement, values) {
        datalistElement.innerHTML = ''; 
        const uniqueValues = [...new Set(values)]; 
        uniqueValues.forEach(value => {
            const option = document.createElement('option');
            option.value = value;
            datalistElement.appendChild(option);
        });
    }

    specializationInput.addEventListener('input', function() {
        const selectedSpecialization = this.value.trim().toLowerCase();

        const filteredDoctors = doctors
            .filter(doc => doc.specialization.toLowerCase() === selectedSpecialization)
            .map(doc => doc.name);

        updateDatalist(doctorsDatalist, filteredDoctors);
    });

        doctorInput.addEventListener('input', function() {
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
            sessionStorage.setItem('doc_id', button.dataset.docId);
            sessionStorage.setItem('doctor_fee', button.dataset.doctorFee);
            sessionStorage.setItem('doctor', button.dataset.doctor);
            sessionStorage.setItem('specialization', button.dataset.specialization);
            sessionStorage.setItem('appointment_id', button.dataset.appointmentId);
            sessionStorage.setItem('start_time', button.dataset.startTime);
            sessionStorage.setItem('day', button.dataset.day);

            const allButtons = document.querySelectorAll('.date-btn');
            allButtons.forEach(btn => btn.classList.remove('selected'));

            button.classList.add('selected');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const patientForm = document.getElementById('appointment-form-patient');

            patientForm.addEventListener('submit', function(event) {
                event.preventDefault(); 

                const patientNIC = document.getElementById('patient_nic').value.trim();
                const patientName = document.getElementById('patient_name').value.trim();

                const appointmentData = {
                    doc_id: sessionStorage.getItem('doc_id'),
                    doctor_fee: sessionStorage.getItem('doctor_fee'),
                    doctor: sessionStorage.getItem('doctor'),
                    specialization: sessionStorage.getItem('specialization'),
                    appointment_id: sessionStorage.getItem('appointment_id'),
                    start_time: sessionStorage.getItem('start_time'),
                    day: sessionStorage.getItem('day'),
                    patient_nic: patientNIC,
                    patient_name: patientName
                };

                console.log('Sending data:', appointmentData); 

                fetch(`<?= ROOT ?>/Receptionist/makeAppointment`, { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(appointmentData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = '" . ROOT . "/Receptionist/todayAppointments';  
                    } else {
                        alert(data.message);  
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

</script>
</html>
