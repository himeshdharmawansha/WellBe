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
                                                    <label class="upload-btn" for="file-input-<?= $detail['test_name'] ?>">
                                                        Upload
                                                        <i id="icon-<?= $detail['test_name'] ?>" class="fa-solid fa-circle-check" style="display: none; color: green; margin-right: 1.5px;" data-file-exists="<?= !empty($detail['file']) ? 'true' : 'false' ?>"></i>
                                                    </label>
                                                    <input type="file" name="file" id="file-input-<?= $detail['test_name'] ?>" class="file-input" data-test-name="<?= $detail['test_name'] ?>" style="display: none;" <?= !empty($detail['file']) ? 'disabled' : '' ?>>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="eye-btn" id="eye-btn-<?= $detail['test_name'] ?>" style="margin-right: 2px;" data-request-id="<?= $requestID ?>" data-test-name="<?= $detail['test_name'] ?>">
                                                    <i id="eye-icon-<?= $detail['test_name'] ?>" class="fa-solid fa-eye" style="color: green; padding: 5px;"></i>
                                                </button>
                                                <button class="delete-btn" id="delete-btn-<?= $detail['test_name'] ?>" data-request-id="<?= $requestID ?>" data-test-name="<?= $detail['test_name'] ?>">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.test-list tbody tr');

            rows.forEach(row => {
                const testName = row.querySelector('.state-selector').dataset.testName;
                const uploadedIcon = document.getElementById(`icon-${testName}`);
                const eyeBtn = row.querySelector(`#eye-btn-${testName}`);
                const deleteBtn = row.querySelector(`#delete-btn-${testName}`);

                if (uploadedIcon && uploadedIcon.dataset.fileExists === "true") {
                    uploadedIcon.style.display = 'inline';
                    eyeBtn.style.display = 'inline';
                    deleteBtn.style.display = 'inline';
                }

                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        const requestID = deleteBtn.dataset.requestId;
                        const testName = deleteBtn.dataset.testName;

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
                                    alert('File deleted successfully!');
                                    uploadedIcon.style.display = 'none';
                                } else {
                                    alert('No file to delete.');
                                }
                            })
                            .catch(error => console.error('Error:', error));
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
                                } else {
                                    alert('No file to open.');
                                }
                            })
                            .catch(error => console.error('Error:', error));
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
                    alert('Error: Missing request ID or patient ID.');
                    return;
                }

                if (rows.length === 0) {
                    alert('Error: No test details found.');
                    return;
                }

                rows.forEach(row => {
                    const testName = row.querySelector('.state-selector').dataset.testName;
                    const state = row.querySelector('.state-selector').value;
                    const fileInput = document.getElementById(`file-input-${testName}`);
                    const file = fileInput.files[0];

                    console.log('Test Name:', testName, 'State:', state);
                    console.log('File Input Disabled:', fileInput.disabled);
                    console.log('File Selected:', file ? file.name : 'No file');

                    tests.push({
                        testName,
                        state,
                        patientID
                    });
                    testNames.push(testName);

                    if (state !== 'completed') {
                        allCompleted = false;
                    }

                    if (file) {
                        formData.append(testName, file);
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
                    alert('Error: No test names found.');
                    return;
                }

                formData.append('tests', JSON.stringify(tests));

                // Update test details
                fetch('<?= ROOT ?>/testRequests/updateRequestDetails', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => {
                        console.log('Update Response Status:', response.status);
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Update Response Data:', data);
                        if (data.success) {
                            alert('Test details updated successfully!');
                            if (allCompleted) {
                                const emailPayload = {
                                    requestID,
                                    patientID,
                                    testNames
                                };
                                console.log('Sending email with:', emailPayload);
                                fetch('<?= ROOT ?>/testRequests/sendCompletionEmail', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify(emailPayload)
                                    })
                                    .then(response => {
                                        console.log('Email Response Status:', response.status);
                                        if (!response.ok) {
                                            return response.text().then(text => {
                                                throw new Error(`HTTP error sending email! Status: ${response.status}, Response: ${text}`);
                                            });
                                        }
                                        return response.json();
                                    })
                                    .then(emailData => {
                                        console.log('Email Response Data:', emailData);
                                        if (emailData.success) {
                                            alert('Completion email sent successfully!');
                                        } else {
                                            alert('Failed to send completion email: ' + (emailData.error || 'Unknown error'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error sending email:', error);
                                        alert('An error occurred while sending the completion email: ' + error.message);
                                    });
                            } else {
                                console.log('Not all tests are completed, skipping email.');
                            }
                            window.location.reload();
                        } else {
                            alert('Failed to update test details: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error updating test details:', error);
                        alert('An error occurred while updating test details: ' + error.message);
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

                    if (this.value === 'completed') {
                        fileInput.disabled = false;
                    } else {
                        fileInput.disabled = true;
                    }
                });

                const testName = select.dataset.testName;
                const fileInput = document.getElementById(`file-input-${testName}`);
                if (select.value === 'completed') {
                    fileInput.disabled = false;
                } else {
                    fileInput.disabled = true;
                }
            });

            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const icon = document.getElementById(`icon-${this.dataset.testName}`);
                    if (icon) {
                        if (this.files.length > 0) {
                            icon.style.display = 'inline';
                        } else {
                            icon.style.display = 'none';
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
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
</body>

</html>