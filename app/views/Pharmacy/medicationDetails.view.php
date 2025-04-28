<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WELLBE</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/medicationDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php
        $this->renderComponent('navbar', $active);
        ?>
        <div class="main-content">
            <?php
            $pageTitle = "Medication Requests";
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>
            <div class="dashboard-content">
                <h2>MEDICINES NEED TO BE GIVEN:</h2>
                <?php
                if ($requestID && $doctorID && $patientID && !empty($medicationDetails)) {
                    echo "<table class='medication-table'>
                            <thead>
                                <tr>
                                    <th>Name of the Medication</th>
                                    <th>Dosage of the Medication</th>
                                    <th colspan='4'>Number taken at a time</th>
                                    <th>Subsititution</th>
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
                            <tbody>";

                    foreach ($medicationDetails as $medication) {
                        $takenTimeArray = explode(' ', $medication['taken_time']);
                        $morning = $takenTimeArray[0] ?? 0;
                        $noon = $takenTimeArray[1] ?? 0;
                        $night = $takenTimeArray[2] ?? 0;
                        $ifNeeded = $takenTimeArray[3] ?? 0;
                        $substitution = $medication['substitution'] == 0 ? "Not Allowed" : "Allowed";

                        $currentState = esc($medication['state']);

                        echo "<tr>
                                <td>{$medication['medication_name']}</td>
                                <td>{$medication['dosage']}</td>
                                <td>{$morning}</td>
                                <td>{$noon}</td>
                                <td>{$night}</td>
                                <td>{$ifNeeded}</td>
                                <td>{$substitution}</td>
                                <td>
                                    <select>
                                        <option disabled selected value=''>choose</option>
                                        <option value='given' " . ($currentState === 'given' ? 'selected' : '') . ">Given</option>
                                        <option value='notavailable' " . ($currentState === 'notavailable' ? 'selected' : '') . ">Not available</option>
                                    </select>
                                </td>
                              </tr>";
                    }

                    echo "</tbody>
                            </table>";

                    echo "<div class='remarks-section'>
                            <h3>Remarks</h3>
                            <p>Date: <span id='currentDate'></span></p>
                            <textarea id='additionalRemarks' placeholder='Enter additional remarks...'>" . htmlspecialchars($additionalRemarks) . "</textarea>
                          </div>";

                    echo "<div class='buttons'>
                            <button class='btn done' id='doneButton' data-request-id={$requestID}>Done</button>
                            <button class='btn remarks' id='remarksButton'>Print</button>
                          </div>";
                } else {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showPopup('Missing or invalid request details. Please provide valid Request ID, Doctor ID, and Patient ID.');
                            });
                          </script>";
                }
                ?>
            </div>
        </div>
        <div class="popup" id="error-popup">
            <span class="close-btn" onclick="closePopup()">×</span>
            <span id="popup-message"></span>
        </div>
    </div>
    <script src="<?= ROOT ?>/assets/js/Pharmacy/remarkPopup.js"></script>
    <script>
        function showPopup(message, type = 'error') {
            const popup = document.getElementById('error-popup');
            const popupMessage = document.getElementById('popup-message');
            if (!popup || !popupMessage) {
                console.error('Popup elements not found');
                return;
            }
            popupMessage.textContent = message;
            popup.className = `popup ${type} active`;
            
            setTimeout(() => {
                popup.className = 'popup';
            }, 5000);
        }

        function closePopup() {
            const popup = document.getElementById('error-popup');
            if (popup) {
                popup.className = 'popup';
            }
        }

        document.getElementById('currentDate').textContent = new Date().toLocaleDateString();

        document.addEventListener('DOMContentLoaded', function() {
            const doneButton = document.getElementById('doneButton');
            if (doneButton) {
                doneButton.addEventListener('click', function() {
                    const requestID = doneButton.getAttribute('data-request-id');
                    const remarks = document.getElementById('additionalRemarks').value;
                    const rows = document.querySelectorAll('.medication-table tbody tr');

                    if (remarks.length > 500) {
                        showPopup('Remarks exceed maximum length of 500 characters');
                        return;
                    }

                    const medications = [];
                    let allStatesSelected = true;
                    rows.forEach(row => {
                        const medicationName = row.cells[0].textContent.trim();
                        const state = row.querySelector('select').value;
                        if (!state) {
                            allStatesSelected = false;
                        }
                        medications.push({
                            medicationName,
                            state,
                        });
                    });

                    if (!allStatesSelected) {
                        showPopup('Please select a state for all medications');
                        return;
                    }

                    fetch('<?= ROOT ?>/MedicationRequests/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            requestID,
                            remarks,
                            medications,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showPopup('Medication request updated successfully', 'success');
                            setTimeout(() => {
                                window.location.href = 'requests';
                            }, 2000);
                        } else {
                            showPopup(data.error || 'Failed to update medication request. Please try again');
                        }
                    })
                    .catch(error => {
                        showPopup('Error updating medication request. Please try again');
                        console.error('Error:', error);
                    });
                });
            }

            const printButton = document.getElementById('remarksButton');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    window.print();
                });
            }
        });
    </script>
</body>
</html>