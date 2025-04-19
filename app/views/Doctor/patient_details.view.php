
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medication Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Medication_Details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
    <?php
        $this->renderComponent('navbar', $active);
        ?>
     

        <!-- Main Content -->
        <div class="main-content">

            <?php
            $pageTitle = "Medical Records";
            require '../app/views/Components/Doctor/header.php';
            ?>
           
            <!-- Dashboard Content -->
            <div class="dashboard-content" style="position:relative">

                 <div class="dashboard-header">
                    <div class="button-container">
                        <a href="<?= ROOT ?>/doctor/medication_Details/<?= $_SESSION['appointment_id']; ?>/<?= $_SESSION['patient_id']; ?>" class="btn btn-green">
                            Create New Record
                        </a>
                    </div>
                  </div>

                  <div class="record-navigation">
                        <?php if (count($data['past_records']) <= 1): ?>
                            <button id="prev-btn" class="nav-btn-inactive" onclick="shiftMedication(-1)">&#8249;</button>
                            <button id="next_btn" class="nav-btn-inactive" onclick="shiftMedication(1)">&#8250;</button>
                        <?php else: ?>
                            <button id="prev-btn" class="nav-btn" onclick="shiftMedication(-1)">&#8249;</button>
                            <button id="next_btn" class="nav-btn" onclick="shiftMedication(1)">&#8250;</button>
                        <?php endif; ?>
                    </div>
                  
                  <div style="display: flex; justify-content: space-between; gap: 30px;margin-top:2%;margin-bottom:3%">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="dr-name">Doctor's Name:</label>
                            <p id="doctor-name" style="width: 20vw; font-weight: bold;">Dr. <?= htmlspecialchars($data['past_records'][0]->doctor); ?></p>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="diagnosis">Diagnosis:</label>
                            <p id="diagnosis" style="width: 17vw; font-weight: bold;"><?= htmlspecialchars($data['past_records'][0]->diagnosis); ?></p>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="date">Date:</label>
                            <p id="record-date" style="width: 17vw;"; ><?= htmlspecialchars(date('d/m/Y', strtotime($data['past_records'][0]->date))); ?></p>
                        </div>
                    </div>


                    <h2>MEDICINES NEED TO BE GIVEN:</h2>
                    <!-- Medication Table -->
                    <table class="medication-table">
                        <thead>
                            <tr>
                                <th>Name of the Medication</th>
                                <th>Dosage of the Medication(mg)</th>
                                <th colspan="4">Number taken at a time</th>
                                <th>Do not substitute</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Morning</th>
                                <th>Noon</th>
                                <th>Night</th>
                                <th>If Needed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="medication-body">
                            <?php 
                                $medications = json_decode($data['past_records'][0]->medications, true);
                                foreach ($medications as $medication):
                                    list($morning, $noon, $night, $if_needed) = explode(' ', $medication['taken_time']);
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($medication['medication_name']); ?></td>
                                    <td><?= htmlspecialchars($medication['dosage']); ?></td>
                                    <td><?= htmlspecialchars($morning); ?></td>
                                    <td><?= htmlspecialchars($noon); ?></td>
                                    <td><?= htmlspecialchars($night); ?></td>
                                    <td><?= htmlspecialchars($if_needed); ?></td>
                                    <td><input type="checkbox" disabled <?= $medication['substitution'] == "1" ? 'checked' : ''; ?>></td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Remarks Section -->
                    <div class="remarks-section" style="margin-bottom: 3%;">
                        <h3>Remarks</h3>
                        <textarea id="additionalRemarks" readonly>
                            Please continue the medicine for 7 days, if you do not see a change please consult again
                        </textarea>
                    </div>

                    <!-- Lab Tests -->
                    <div class="record-navigation">
                        <?php if (count($data) <= 1): ?>
                            <button id="prev-btn" class="nav-btn-inactive" onclick="shiftLabTest(-1)">&#8249;</button>
                            <button id="next_btn" class="nav-btn-inactive" onclick="shiftLabTest(1)">&#8250;</button>
                        <?php else: ?>
                            <button id="prev-btn" class="nav-btn" onclick="shiftLabTest(-1)">&#8249;</button>
                            <button id="next_btn" class="nav-btn" onclick="shiftLabTest(1)">&#8250;</button>
                        <?php endif; ?>
                    </div>
                  
                  <div style="display: flex; justify-content: space-between; gap: 30px;margin-top:2%;margin-bottom:3%">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="dr-name">Doctor's Name:</label>
                            <p id="doctor-name" style="width: 20vw; font-weight: bold;">Dr. <?= htmlspecialchars($data['past_tests'][0]->doctor); ?></p>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="date">Date:</label>
                            <p id="record-date" style="width: 17vw;"; ><?= htmlspecialchars(date('d/m/Y', strtotime($data['past_tests'][0]->date))); ?></p>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Name of the Test</th>
                                <th>Priority Level</th>
                                <th>Report</th>
                            </tr>
                        </thead>
                        <tbody id="labtest-body">
                            <?php 
                                $labTests = json_decode($data['past_tests'][0]->tests, true);
                                foreach ($labTests as $test):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($test['test_name']); ?></td>
                                    <td><?= htmlspecialchars($test['priority']); ?></td>
                                    <td>Not available</td> <!-- Update if you plan to link reports later -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
               
               </div>
         </div>
   
      </div>
                 
    </div>

    <script>
        const records = <?= json_encode($data['past_records']); ?>;
        const labTests = <?= json_encode($data['past_tests']); ?>;

        let medicationIndex = 0;
        let labTestIndex = 0;

        // --- Update Medication Section ---
        function updateMedicationsView(index) {
            const record = records[index];
            document.getElementById('doctor-name').innerText = `Dr. ${record.doctor}`;
            document.getElementById('record-date').innerText = new Date(record.date).toLocaleDateString('en-GB');
            document.getElementById('diagnosis').innerText = record.diagnosis;

            const medicationBody = document.getElementById('medication-body');
            medicationBody.innerHTML = '';

            try {
                const medications = JSON.parse(record.medications);
                medications.forEach(med => {
                    const [morning, noon, night, ifNeeded] = med.taken_time.split(' ');
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${med.medication_name}</td>
                        <td>${med.dosage}</td>
                        <td>${morning}</td>
                        <td>${noon}</td>
                        <td>${night}</td>
                        <td>${ifNeeded}</td>
                        <td><input type="checkbox" disabled ${med.substitution === "1" ? 'checked' : ''}></td>
                    `;
                    medicationBody.appendChild(row);
                });
            } catch (error) {
                console.error('Failed to parse medications:', error);
            }

            document.getElementById('prev-btn').disabled = index === 0;
            document.getElementById('next_btn').disabled = index === records.length - 1;
        }

        // --- Update Lab Tests Section ---
        function updateLabTestsView(index) {
            console.log(labTests);
            // Parse the tests string into an array, or use an empty array if undefined
            const tests = labTests[index] && labTests[index].tests
                ? JSON.parse(labTests[index].tests)
                : [];
            const labTestBody = document.getElementById('labtest-body');
            labTestBody.innerHTML = '';

            // Iterate over the tests array and display the test name and priority
            tests.forEach(test => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${test.test_name}</td>
                    <td>${test.priority}</td>
                    <td>Not available</td> <!-- Placeholder for report status -->
                `;
                labTestBody.appendChild(row);
            });

            // Navigation buttons for lab tests
            document.getElementById('prev-lab-btn').disabled = index === 0;
            document.getElementById('next-lab-btn').disabled = index === labTests.length - 1;
        }

        // --- Navigation Functions ---
        function shiftMedication(direction) {
            const newIndex = medicationIndex + direction;
            if (newIndex >= 0 && newIndex < records.length) {
                medicationIndex = newIndex;
                updateMedicationsView(medicationIndex);
            }
        }

        function shiftLabTest(direction) {
            const newIndex = labTestIndex + direction;
            if (newIndex >= 0 && newIndex < labTests.length) {
                labTestIndex = newIndex;
                updateLabTestsView(labTestIndex);
            }
        }

        // --- On Page Load ---
        window.onload = function () {
            updateMedicationsView(medicationIndex);
            updateLabTestsView(labTestIndex);
        };
    </script>




</body>
</html>
              