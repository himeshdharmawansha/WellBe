<?php
foreach ($medDetails as $med) {
    // Split the taken_time by spaces
    $takenTimes = preg_split('/\s+/', trim($med->taken_time));

    // Assign actual numbers to variables (default to '0' if not set)
    $med->morning = $takenTimes[0] ?? '0';
    $med->noon = $takenTimes[1] ?? '0';
    $med->night = $takenTimes[2] ?? '0';
    $med->if_needed = $takenTimes[3] ?? '0';
}
?>

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
        <?php $this->renderComponent('navbar', $active); ?>

        <!-- Main Content -->
        <div class="main-content">
            <?php
            $pageTitle = "Medical Records";
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/header.php';
            ?>

            <div class="dashboard-content">
                <?php if (isset($requests)): ?>
                    <?php foreach ($requests as $req): ?>
                        <label for="dr-name">Doctor's Name:</label>
                        <p><?= $req->doctor_first_name ?> <?= $req->doctor_last_name ?></p>
                        <label for="date">Date:</label>
                        <p><?= $req->date ?></p>
                        <label for="diagnosis">Diagnosis:</label>
                        <p><?= $req->diagnosis ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>

                <h2>MEDICINES NEED TO BE GIVEN:</h2>

                <?php if (isset($medDetails) && !empty($medDetails)): ?>
                    <table class="medication-table">
                        <thead>
                            <tr>
                                <th>Name of the Medication</th>
                                <th>Dosage of the Medication</th>
                                <th colspan="4">Number taken at a time</th>
                                <th>Substitution</th>
                                <!-- <th>State</th> -->
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Morning</th>
                                <th>Noon</th>
                                <th>Night</th>
                                <th>If Needed</th>
                                <th></th>
                                <!-- <th></th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <!-- <pre><?php print_r($medDetails); ?></pre> -->
                            <?php foreach ($medDetails as $med): ?>

                                <tr>
                                    <td><?= $med->medication_name ?></td>
                                    <td><?= $med->dosage ?></td>
                                    <td><?= $med->morning ?></td>
                                    <td><?= $med->noon ?></td>
                                    <td><?= $med->night ?></td>
                                    <td><?= $med->if_needed ?></td>
                                    <td><?= isset($med->substitution) && $med->substitution == 1 ? 'Not Allowed' : 'Allowed' ?></td>
                                    <!-- <td>
                                        <select>
                                            <option value="pending" selected>Pending</option>
                                            <option value="given">Given</option>
                                            <option value="notavailable">Not available</option>
                                        </select>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No medication details available.</p>
                <?php endif; ?>

                <!-- <h2>TESTS NEED TO BE TAKEN:</h2>
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
                </table> -->

                <div class="remarks-section">
                    <h3>Remarks</h3>
                    <textarea id="additionalRemarks" readonly><?= isset($medDetails[0]->remark) ? $medDetails[0]->remark : 'No remarks available.' ?></textarea>
                </div>
                <div class="back-button-container">
                    <button onclick="history.back()">Back</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doneButton = document.getElementById('doneButton');

            if (doneButton) {
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

                    console.log({
                        requestID,
                        remarks,
                        medications
                    });

                    window.location.href = 'requests';
                });
            }
        });
    </script>
</body>

</html>