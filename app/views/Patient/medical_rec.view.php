<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Patient Medication Details</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/medical_rec.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <div class="dashboard-container">
        <?php
        $this->renderComponent('navbar', $active);
        ?>

        <!-- Main Content -->
        <div class="main-content">
            <?php
            $pageTitle = "Medical Records"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <label for="dr-name">Doctor's Name:</label>
                <p>Dr. John</p>
                <label for="date">Date:</label>
                <p>24/5/2024</p>
                <label for="diagnosis">Diagnosis:</label>
                <p>Gastritis</p>
                <h2>MEDICINES NEED TO BE GIVEN:</h2>

                <table class="medication-table">
                    <thead>
                        <tr>
                            <th>Name of the Medication</th>
                            <th>Dosage of the Medication</th>
                            <th colspan="4">Number taken at a time</th>
                            <th>Do not substitute</th>
                            <th>State</th>
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
                    <tbody>
                        <tr>
                            <td>Omeprazole</td>
                            <td>20mg</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>can't</td>
                            <td>
                                <select>
                                    <option value="pending" selected>Pending</option>
                                    <option value="given">Given</option>
                                    <option value="notavailable">Not available</option>
                                </select>
                            </td>
                        </tr>
                        <!-- Add more rows if needed -->
                    </tbody>
                </table>

                <h2>TESTS NEED TO BE TAKEN:</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name of the Test</th>
                            <th>Priority Level</th>
                            <th>Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>FBC</td>
                            <td>High</td>
                            <td><button class="view" onclick="window.location.href='Lab_download'">View</button></td>
                        </tr>
                        <tr>
                            <td>FBC</td>
                            <td>Medium</td>
                            <td><button class="view" onclick="window.location.href='Lab_download'">View</button></td>
                        </tr>
                        <tr>
                            <td>FBC</td>
                            <td>Low</td>
                            <td><button class="pending">Pending</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="remarks-section">
                    <h3>Remarks</h3>
                    <textarea id="additionalRemarks" readonly>
Please continue the medicine for 7 days, if you do not see a change please consult again
          </textarea>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Set the current date dynamically in the remarks section
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString();

        document.addEventListener('DOMContentLoaded', function() {
            const doneButton = document.getElementById('doneButton');

            doneButton.addEventListener('click', function() {
                const requestID = doneButton.getAttribute('data-request-id');
                const remarks = document.getElementById('additionalRemarks').value;
                const rows = document.querySelectorAll('.medication-table tbody tr');

                const medications = [];
                rows.forEach(row => {
                    const medicationName = row.cells[0].textContent.trim();
                    const state = row.querySelector('select').value;

                    medications.push({
                        medicationName,
                        state,
                    });
                });

                // Simulate sending data
                console.log({
                    requestID,
                    remarks,
                    medications
                });

                // Simulate redirect
                window.location.href = 'requests';
            });
        });
    </script>
</body>

</html>