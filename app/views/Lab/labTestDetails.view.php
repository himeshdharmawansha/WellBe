<?php
require_once(__DIR__ . "/../../controllers/TestRequests.php");
$he = new TestRequests();
$requestID = isset($_GET['ID']) ? htmlspecialchars($_GET['ID']) : null;
$testDetails = $he->getTestDetails($requestID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Lab/labTestDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php $this->renderComponent('navbar', $active); ?>
        <div class="main-content">
            <?php $pageTitle = "Test Requests"; ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php'; ?>
            <div class="dashboard-content">
                <h2>Tests For Patient_ID: <?= $_GET['patient_id'] ?></h2>

                <?php if (isset($_GET['patient_id'])): ?>
                    <?php $patientID = $_GET['patient_id']; ?>
                    <input type="hidden" id="patient_id" value="<?= $_GET['patient_id'] ?>">
                    <div class="test-list" style="max-height: 450px; max-width: 800px;">
                        <table style="width: 100%; border-spacing: 0 10px;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; width: 45%;">Test Name</th>
                                    <th style="text-align: left; width: 15%;">Priority</th>
                                    <th style="text-align: left; width: 15%;">State</th>
                                    <th style="text-align: left; width: 15%;">Upload File</th>
                                    <th style="text-align: left; width: 10%;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($testDetails)): ?>
                                    <?php foreach ($testDetails as $detail): ?>
                                        <tr data-request-id="<?= esc($requestID) ?>">
                                            <td><?= esc($detail['test_name']) ?></td>
                                            <td><?= esc($detail['priority']) ?></td>
                                            <td>
                                                <select name="state" data-test-name="<?= $detail['test_name'] ?>" class="state-selector">
                                                    <option value="pending" <?= $detail['state'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="ongoing" <?= $detail['state'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                                    <option value="completed" <?= $detail['state'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                                </select>
                                            </td>
                                            <td>
                                                <form class="upload-form" data-test-name="<?= $detail['test_name'] ?>" data-request-id="<?= $requestID ?>" enctype="multipart/form-data">
                                                    <label class="upload-btn" id="upload-btn-<?= $detail['test_name'] ?>" for="file-input-<?= $detail['test_name'] ?>" style="opacity: <?= $detail['state'] == 'completed' ? '1' : '0.5'; ?>">
                                                        Upload
                                                        <i id="icon-<?= $detail['test_name'] ?>" class="fa-solid fa-circle-check" style="display: none; color: green; margin-right: 1.5px;" data-file-exists="<?= !empty($detail['file']) ? 'true' : 'false' ?>"></i>
                                                    </label>
                                                    <input type="file" name="file" id="file-input-<?= $detail['test_name'] ?>" class="file-input" data-test-name="<?= $detail['test_name'] ?>" style="display: none;" <?= !empty($detail['file']) ? 'disabled' : '' ?>>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="eye-btn" id="eye-btn-<?= $detail['test_name'] ?>" style="margin-right: 2px; opacity: <?= !empty($detail['file']) ? '1' : '0.5'; ?>;" data-request-id="<?= $requestID ?>" data-test-name="<?= $detail['test_name'] ?>" <?= !empty($detail['file']) ? '' : 'disabled' ?>>
                                                    <i id="eye-icon-<?= $detail['test_name'] ?>" class="fa-solid fa-eye" style="color: green; padding: 5px;"></i>
                                                </button>
                                                <button class="delete-btn" id="delete-btn-<?= $detail['test_name'] ?>" style="opacity: <?= !empty($detail['file']) ? '1' : '0.5'; ?>;" data-request-id="<?= $requestID ?>" data-test-name="<?= $detail['test_name'] ?>" <?= !empty($detail['file']) ? '' : 'disabled' ?>>
                                                    <i id="trash-icon-<?= $detail['test_name'] ?>" class="fa-solid fa-trash" style="color: red; padding: 5px;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center;">No test details found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="button-container">
                        <button class="completed-btn" id="doneButton" data-request-id="<?= $requestID ?>">Completed</button>
                    </div>

                <?php else: ?>
                    <p>Invalid patient ID.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Error/Success Popup -->
        <div class="popup" id="error-popup">
            <span class="close-btn" onclick="closePopup()">×</span>
            <span id="popup-message"></span>
        </div>
        <!-- Confirmation Popup -->
        <div class="confirm-popup" id="confirm-popup">
            <span id="confirm-message"></span>
            <div class="confirm-buttons">
                <button id="confirm-yes" class="confirm-btn yes-btn">Yes</button>
                <button id="confirm-no" class="confirm-btn no-btn">No</button>
            </div>
        </div>
    </div>

    <script>
        // Define showPopup and closePopup in the global scope
        function showPopup(message, type = 'error') {
            const popup = document.getElementById('error-popup');
            const popupMessage = document.getElementById('popup-message');
            if (!popup || !popupMessage) {
                console.error('Popup elements not found:', { popup, popupMessage });
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

        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation popup logic
            function showConfirmPopup(message, onConfirm) {
                const confirmPopup = document.getElementById('confirm-popup');
                const confirmMessage = document.getElementById('confirm-message');
                const confirmYes = document.getElementById('confirm-yes');
                const confirmNo = document.getElementById('confirm-no');

                confirmMessage.textContent = message;
                confirmPopup.classList.add('active');

                const handleConfirm = () => {
                    onConfirm();
                    confirmPopup.classList.remove('active');
                    confirmYes.removeEventListener('click', handleConfirm);
                    confirmNo.removeEventListener('click', handleCancel);
                };

                const handleCancel = () => {
                    confirmPopup.classList.remove('active');
                    confirmYes.removeEventListener('click', handleConfirm);
                    confirmNo.removeEventListener('click', handleCancel);
                };

                confirmYes.addEventListener('click', handleConfirm);
                confirmNo.addEventListener('click', handleCancel);
            }

            const rows = document.querySelectorAll('.test-list tbody tr');

            rows.forEach(row => {
                const testName = row.querySelector('.state-selector').dataset.testName;
                const uploadedIcon = document.getElementById(`icon-${testName}`);
                const eyeBtn = row.querySelector(`#eye-btn-${testName}`);
                const deleteBtn = row.querySelector(`#delete-btn-${testName}`);

                // Show pass icon if a file exists initially
                if (uploadedIcon && uploadedIcon.dataset.fileExists === "true") {
                    uploadedIcon.style.display = 'inline';
                    eyeBtn.style.opacity = '1'; // Full opacity for eye button
                    eyeBtn.disabled = false; // Enable eye button
                    deleteBtn.style.opacity = '1'; // Full opacity for delete button
                    deleteBtn.disabled = false; // Enable delete button
                }

                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        const requestID = deleteBtn.dataset.requestId;
                        const testName = deleteBtn.dataset.testName;

                        showConfirmPopup('Are you sure you want to delete this file?', () => {
                            fetch('<?= ROOT ?>/testRequests/deleteFile', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        requestID,
                                        testName
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        showPopup('File deleted successfully!', 'success');
                                        uploadedIcon.style.display = 'none';
                                        uploadedIcon.dataset.fileExists = 'false'; // Reset file-exists flag
                                        eyeBtn.style.opacity = '0.5'; // Dim eye button
                                        eyeBtn.disabled = true; // Disable eye button
                                        deleteBtn.style.opacity = '0.5'; // Dim delete button
                                        deleteBtn.disabled = true; // Disable delete button
                                        const fileInput = document.getElementById(`file-input-${testName}`);
                                        fileInput.disabled = false;
                                        fileInput.value = ''; // Clear the file input
                                    } else {
                                        showPopup('No file to delete.');
                                    }
                                })
                                .catch(error => {
                                    showPopup('Error deleting file: ' + error.message);
                                    console.error('Error:', error);
                                });
                        });
                    });
                }

                if (eyeBtn) {
                    eyeBtn.addEventListener('click', function() {
                        const requestID = eyeBtn.dataset.requestId;
                        const testName = eyeBtn.dataset.testName;

                        fetch('<?= ROOT ?>/testRequests/getFileUrl', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    requestID,
                                    testName
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.open(data.fileUrl, '_blank');
                                    showPopup('File opened successfully', 'success');
                                } else {
                                    showPopup('No file to open.');
                                }
                            })
                            .catch(error => {
                                showPopup('Error opening file: ' + error.message);
                                console.error('Error:', error);
                            });
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const doneButton = document.getElementById('doneButton');

            doneButton.addEventListener('click', function() {
                const requestID = doneButton.getAttribute('data-request-id');
                const patientID = document.getElementById('patient_id').value;
                const rows = document.querySelectorAll('.test-list tbody tr');

                const formData = new FormData();
                formData.append('requestID', requestID);
                formData.append('patientID', patientID);

                const tests = [];
                const testNames = [];
                let allCompleted = true;

                console.log('Request ID:', requestID);
                console.log('Patient ID:', patientID);
                console.log('Rows length:', rows.length);

                if (!requestID || !patientID) {
                    showPopup('Error: Missing request ID or patient ID.');
                    return;
                }

                if (rows.length === 0) {
                    showPopup('Error: No test details found.');
                    return;
                }

                rows.forEach(row => {
                    const testName = row.querySelector('.state-selector').dataset.testName;
                    const state = row.querySelector('.state-selector').value;
                    const fileInput = document.getElementById(`file-input-${testName}`);
                    const file = fileInput.files[0];
                    const uploadedIcon = document.getElementById(`icon-${testName}`);
                    const eyeBtn = row.querySelector(`#eye-btn-${testName}`);
                    const deleteBtn = row.querySelector(`#delete-btn-${testName}`);

                    console.log('Test Name:', testName, 'State:', state);
                    console.log('File Input Disabled:', fileInput.disabled);
                    console.log('File Selected:', file ? file.name : 'No file');

                    tests.push({
                        testName: testName,
                        state: state,
                        patientID: patientID
                    });
                    testNames.push(testName);

                    if (state !== 'completed') {
                        allCompleted = false;
                    }

                    if (file) {
                        formData.append(testName, file);
                        if (uploadedIcon) {
                            uploadedIcon.dataset.fileExists = 'true'; // Update file-exists flag on upload
                            uploadedIcon.style.display = 'inline'; // Ensure pass icon is visible
                            eyeBtn.style.opacity = '1'; // Full opacity for eye button
                            eyeBtn.disabled = false; // Enable eye button
                            deleteBtn.style.opacity = '1'; // Full opacity for delete button
                            deleteBtn.disabled = false; // Enable delete button
                        }
                    }
                });

                // Log FormData contents
                for (let [key, value] of formData.entries()) {
                    console.log(`FormData Entry - ${key}:`, value);
                }

                console.log('Tests:', tests);
                console.log('Test Names:', testNames);
                console.log('All Completed:', allCompleted);

                if (testNames.length === 0) {
                    showPopup('Error: No test names found.');
                    return;
                }

                formData.append('tests', JSON.stringify(tests));

                // Update test details
                fetch('<?= ROOT ?>/testRequests/updateRequestDetails', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Update Response Data:', data);
                        if (data.success) {
                            showPopup('Test details updated successfully!', 'success');
                            if (allCompleted) {
                                const emailPayload = {
                                    requestID: requestID,
                                    patientID: patientID,
                                    testNames: testNames
                                };
                                console.log('Sending email with payload:', emailPayload);
                                fetch('<?= ROOT ?>/testRequests/sendCompletionEmail', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify(emailPayload)
                                    })
                                    .then(emailResponse => {
                                        console.log('Email Response Status:', emailResponse.status);
                                        if (!emailResponse.ok) {
                                            return emailResponse.text().then(text => {
                                                throw new Error(`HTTP error sending email! Status: ${emailResponse.status}, Response: ${text}`);
                                            });
                                        }
                                        return emailResponse.json();
                                    })
                                    .then(emailData => {
                                        console.log('Email Response Data:', emailData);
                                        if (emailData.success) {
                                            showPopup('Completion email sent successfully!', 'success');
                                        } else {
                                            showPopup('Failed to send email, check the network connection');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error sending email:', error);
                                        showPopup('An error occurred while sending the email, check the network connection');
                                    })
                                    .finally(() => {
                                        // Delay the reload to ensure popups are visible
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 3000); // 3-second delay
                                    });
                            } else {
                                console.log('Not all tests are completed, skipping email.');
                                // Delay the reload to ensure popup is visible
                                setTimeout(() => {
                                    window.location.reload();
                                }, 3000); // 3-second delay
                            }
                        } else {
                            showPopup('Failed to update test details: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error updating test details:', error);
                        showPopup('An error occurred while updating test details: ' + error.message);
                    });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('.file-input');
            const stateSelectors = document.querySelectorAll('.state-selector');

            stateSelectors.forEach(select => {
                select.addEventListener('change', function() {
                    const testName = this.dataset.testName;
                    const fileInput = document.getElementById(`file-input-${testName}`);
                    const uploadBtn = document.getElementById(`upload-btn-${testName}`);

                    if (this.value === 'completed') {
                        fileInput.disabled = false;
                        uploadBtn.style.opacity = '1'; // Full opacity when state is completed
                    } else {
                        fileInput.disabled = true;
                        uploadBtn.style.opacity = '0.5'; // Dim when state is not completed
                    }
                });

                const testName = select.dataset.testName;
                const fileInput = document.getElementById(`file-input-${testName}`);
                const uploadBtn = document.getElementById(`upload-btn-${testName}`);
                if (select.value === 'completed') {
                    fileInput.disabled = false;
                    uploadBtn.style.opacity = '1'; // Full opacity if state is completed
                } else {
                    fileInput.disabled = true;
                    uploadBtn.style.opacity = '0.5'; // Dim if state is not completed
                }
            });

            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const testName = this.dataset.testName;
                    const icon = document.getElementById(`icon-${testName}`);
                    const eyeBtn = document.querySelector(`#eye-btn-${testName}`);
                    const deleteBtn = document.querySelector(`#delete-btn-${testName}`);

                    if (icon) {
                        if (this.files.length > 0) {
                            icon.style.display = 'inline';
                            icon.dataset.fileExists = 'true';
                            eyeBtn.style.opacity = '1'; // Full opacity for eye button
                            eyeBtn.disabled = false; // Enable eye button
                            deleteBtn.style.opacity = '1'; // Full opacity for delete button
                            deleteBtn.disabled = false; // Enable delete button
                        } else {
                            icon.style.display = 'none';
                            icon.dataset.fileExists = 'false';
                            eyeBtn.style.opacity = '0.5'; // Dim eye button
                            eyeBtn.disabled = true; // Disable eye button
                            deleteBtn.style.opacity = '0.5'; // Dim delete button
                            deleteBtn.disabled = true; // Disable delete button
                        }
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const stateSelectors = document.querySelectorAll('.state-selector');

            stateSelectors.forEach(selector => {
                selector.addEventListener('change', function() {
                    const requestID = this.closest('tr').dataset.requestId;
                    const newState = this.value;
                    const testName = this.dataset.testName;
                    fetch('<?= ROOT ?>/testRequests/updateState', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                requestID: requestID,
                                state: newState,
                                testName: testName,
                            }),
                        })
                        .then(response => response.json())
                        .catch(error => {
                            showPopup('Error updating state: ' + error.message);
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
</body>

</html>