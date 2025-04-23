<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Patient Medication Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Medication_Details.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            height: 90%;
            max-width: 1200px;
            max-heightters: 800px;
            overflow: auto;
            position: relative;
        }

        /* Report Modal Specific Styles */
        #reportModal {
            z-index: 9999;
            background: rgba(0,0,0,0.8);
        }

        #modalContent {
            width: 95%;
            height: 95%;
            padding: 40px 20px 20px 20px;
            display: flex;
            flex-direction: column;
        }

        #modalContent iframe {
            width: 100%;
            height: 100%;
            border: none;
            transform: scale(1);
            transform-origin: top left;
        }

        /* Close Button Styles */
        .close {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 30px;
            cursor: pointer;
            color: #333;
            transition: color 0.2s;
            z-index: 10000;
            font-weight: bold;
        }

        .close:hover {
            color: #007bff;
        }

        .view-record, .view-pdf-report {
            color: #007bff;
            text-decoration: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .view-record:hover, .view-pdf-report:hover {
            text-decoration: underline;
        }
    </style>
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
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content" style="position:relative">
                <div class="dashboard-header">
                    <div class="button-container">
                        
                    </div>
                </div>

                <!-- Medication Records -->
                <div class="record-navigation">
                    <?php if (empty($data['past_records'])): ?>
                        <button id="prev-btn" class="nav-btn-inactive" disabled>‹</button>
                        <button id="next-btn" class="nav-btn-inactive" disabled>›</button>
                    <?php else: ?>
                        <button id="prev-btn" class="nav-btn" onclick="shiftMedication(-1)">‹</button>
                        <button id="next-btn" class="nav-btn" onclick="shiftMedication(1)">›</button>
                    <?php endif; ?>
                </div>

                <?php if (!empty($data['past_records'])): ?>
                    <div style="display: flex; justify-content: space-between; gap: 30px; margin-top:2%; margin-bottom:3%">
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
                            <p id="record-date" style="width: 17vw;"><?= htmlspecialchars(date('d/m/Y', strtotime($data['past_records'][0]->date))); ?></p>
                        </div>
                    </div>

                    <h2>MEDICINES NEED TO BE GIVEN:</h2>
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
                            if (!empty($medications)):
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
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">No medications recorded</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="remarks-section" style="margin-bottom: 3%;">
                        <h3>Remarks</h3>
                        <textarea id="additionalRemarks" readonly>
                            <?= htmlspecialchars($data['past_records'][0]->remarks ?? 'Please continue the medicine for 7 days, if you do not see a change please consult again'); ?>
                        </textarea>
                    </div>
                <?php else: ?>
                    <h2>MEDICINES NEED TO BE GIVEN:</h2>
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
                            <tr>
                                <td colspan="7" style="text-align: center;">No past records</td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Lab Tests -->
                <div class="record-navigation">
                    <?php if (empty($data['past_tests'])): ?>
                        <button id="prev-lab-btn" class="nav-btn-inactive" disabled>‹</button>
                        <button id="next-lab-btn" class="nav-btn-inactive" disabled>›</button>
                    <?php else: ?>
                        <button id="prev-lab-btn" class="nav-btn" onclick="shiftLabTest(-1)">‹</button>
                        <button id="next-lab-btn" class="nav-btn" onclick="shiftLabTest(1)">›</button>
                    <?php endif; ?>
                </div>

                <?php if (!empty($data['past_tests'])): ?>
                    <div style="display: flex; justify-content: space-between; gap: 30px; margin-top:2%; margin-bottom:3%">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="dr-name">Doctor's Name:</label>
                            <p id="lab-doctor-name" style="width: 20vw; font-weight: bold;">Dr. <?= htmlspecialchars($data['past_tests'][0]->doctor); ?></p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <label for="date">Date:</label>
                            <p id="lab-record-date" style="width: 17vw;"><?= htmlspecialchars(date('d/m/Y', strtotime($data['past_tests'][0]->date))); ?></p>
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
                            $labTestsData = json_decode($data['past_tests'][0]->tests, true);
                            if (!empty($labTestsData)):
                                foreach ($labTestsData as $test):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($test['test_name']); ?></td>
                                    <td><?= htmlspecialchars($test['priority']); ?></td>
                                    <td>Not available</td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center">No tests recorded</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name of the Test</th>
                                <th>Priority Level</th>
                                <th>Report</th>
                            </tr>
                        </thead>
                        <tbody id="labtest-body">
                            <tr>
                                <td colspan="3" style="text-align: center">No past lab tests</td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="reportModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); justify-content: center; align-items: center; z-index: 9999;">
        <div id="modalContent" style="background: #fff; max-width: 95%; max-height: 95%; overflow: auto; border-radius: 8px; position: relative;">
            <span id="closeReportModal" class="close" style="position: absolute; top: 15px; right: 25px; font-size: 30px; cursor: pointer; color: #333;">×</span>
        </div>
    </div>

    <script>
        console.log("Patient Details script loaded - version 2025-04-20");

        const records = <?= json_encode($data['past_records']); ?>;
        const labTests = <?= json_encode($data['past_tests']); ?>;

        let medicationIndex = 0;
        let labTestIndex = 0;

        // Update Medication Section
        function updateMedicationsView(index) {
            const medicationBody = document.getElementById('medication-body');
            medicationBody.innerHTML = '';

            if (!records || records.length === 0) {
                medicationBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No past records</td></tr>';
                document.getElementById('prev-btn').disabled = true;
                document.getElementById('next-btn').disabled = true;
                if (document.getElementById('doctor-name')) document.getElementById('doctor-name').innerText = 'N/A';
                if (document.getElementById('record-date')) document.getElementById('record-date').innerText = 'N/A';
                if (document.getElementById('diagnosis')) document.getElementById('diagnosis').innerText = 'N/A';
                if (document.getElementById('additionalRemarks')) document.getElementById('additionalRemarks').value = '';
                return;
            }

            const record = records[index];
            if (document.getElementById('doctor-name')) document.getElementById('doctor-name').innerText = `Dr. ${record.doctor}`;
            if (document.getElementById('record-date')) document.getElementById('record-date').innerText = new Date(record.date).toLocaleDateString('en-GB');
            if (document.getElementById('diagnosis')) document.getElementById('diagnosis').innerText = record.diagnosis;
            if (document.getElementById('additionalRemarks')) {
                document.getElementById('additionalRemarks').value = record.remarks || 'Please continue the medicine for 7 days, if you do not see a change please consult again';
            }

            try {
                const medications = JSON.parse(record.medications);
                if (medications && medications.length > 0) {
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
                } else {
                    medicationBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No medications recorded</td></tr>';
                }
            } catch (error) {
                console.error('Failed to parse medications:', error);
                medicationBody.innerHTML = '<tr><td colspan="7" style="text-align: center;">Error loading medications</td></tr>';
            }

            document.getElementById('prev-btn').disabled = index === 0;
            document.getElementById('next-btn').disabled = index === records.length - 1;
        }

        // Update Lab Tests Section
        function updateLabTestsView(index) {
            const labTestBody = document.getElementById('labtest-body');
            labTestBody.innerHTML = '';

            if (!labTests || labTests.length === 0) {
                labTestBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No past lab tests</td></tr>';
                document.getElementById('prev-lab-btn').disabled = true;
                document.getElementById('next-lab-btn').disabled = true;
                if (document.getElementById('lab-doctor-name')) document.getElementById('lab-doctor-name').innerText = 'N/A';
                if (document.getElementById('lab-record-date')) document.getElementById('lab-record-date').innerText = 'N/A';
                return;
            }

            const test = labTests[index];
            if (document.getElementById('lab-doctor-name')) document.getElementById('lab-doctor-name').innerText = `Dr. ${test.doctor}`;
            if (document.getElementById('lab-record-date')) document.getElementById('lab-record-date').innerText = new Date(test.date).toLocaleDateString('en-GB');

            try {
                const tests = JSON.parse(test.tests);
                if (tests && tests.length > 0) {
                    tests.forEach(test => {
                        const row = document.createElement('tr');

                        let reportCell;
                        if (test.file && test.file.trim() !== "") {
                            reportCell = `<a href="#" class="view-report" data-file="${test.file}">View Report</a>`;
                        } else {
                            reportCell = `<span style="color: red;">No Report Available</span>`;
                        }

                        row.innerHTML = `
                            <td>${test.test_name}</td>
                            <td>${test.priority}</td>
                            <td>${reportCell}</td>
                        `;
                        labTestBody.appendChild(row);
                    });

                    // Add event listeners to report links
                    document.querySelectorAll('.view-report').forEach(link => {
                        link.addEventListener('click', function (e) {
                            e.preventDefault();
                            const file = this.getAttribute('data-file');
                            const modal = document.getElementById('reportModal');
                            const container = document.getElementById('modalContent');

                            // Insert iframe after the close button
                            const iframe = document.createElement('iframe');
                            iframe.src = `http://localhost/WellBe/public/assets/files/${file}.pdf`;
                            iframe.style.width = '100%';
                            iframe.style.height = '100%';
                            iframe.style.border = 'none';

                            // Clear previous content and append new iframe
                            while (container.firstChild && container.firstChild !== document.getElementById('closeReportModal')) {
                                container.removeChild(container.firstChild);
                            }
                            container.appendChild(iframe);

                            modal.style.display = 'flex';
                        });
                    });
                } else {
                    labTestBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No tests recorded</td></tr>';
                }
            } catch (error) {
                console.error('Failed to parse lab tests:', error);
                labTestBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Error loading lab tests</td></tr>';
            }

            document.getElementById('prev-lab-btn').disabled = index === 0;
            document.getElementById('next-lab-btn').disabled = index === labTests.length - 1;
        }

        // Navigation Functions
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

        // On Page Load
        window.onload = function () {
            console.log("Records:", records);
            console.log("Lab Tests:", labTests);
            updateMedicationsView(medicationIndex);
            updateLabTestsView(labTestIndex);
        };

        // Close Modal Functionality
        const reportModal = document.getElementById('reportModal');
        const closeReportModal = document.getElementById('closeReportModal');

        closeReportModal.addEventListener('click', function() {
            console.log('Closing report modal');
            reportModal.style.display = 'none';
            const modalContent = document.getElementById('modalContent');
            const iframe = modalContent.querySelector('iframe');
            if (iframe) {
                iframe.remove();
            }
        });

        window.addEventListener('click', function(event) {
            if (event.target === reportModal) {
                console.log('Closing report modal (clicked outside)');
                reportModal.style.display = 'none';
                const modalContent = document.getElementById('modalContent');
                const iframe = modalContent.querySelector('iframe');
                if (iframe) {
                    iframe.remove();
                }
            }
        });
    </script>
</body>
</html>