<?php
$patient_id = $data['patient_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $diagnosis = $_POST['diagnosis'];
    $medications = $_POST['medication_name'] ?? [];
    $dosages = $_POST['dosage'] ?? [];
    $morning = $_POST['morning'] ?? [];
    $noon = $_POST['noon'] ?? [];
    $night = $_POST['night'] ?? [];
    $if_needed = $_POST['if_needed'] ?? [];
    $do_not_substitute = $_POST['do_not_substitute'] ?? [];
    $lab_tests = $_POST['test_name'] ?? [];
    $priority = $_POST['priority'] ?? [];
    $remarks = $_POST['remarks'] ?? "";

    // Handle file upload
    $file_path = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'];
        $max_file_size = 5 * 1024 * 1024; // 5MB
        $upload_dir = 'C:\wamp64\www\WellBe\public\assets\files\prescription_documents/';
        
        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                echo "Error: Failed to create upload directory.";
                exit;
            }
        }

        // Check if directory is writable
        if (!is_writable($upload_dir)) {
            echo "Error: Upload directory is not writable.";
            exit;
        }

        $file = $_FILES['document'];
        $file_type = mime_content_type($file['tmp_name']);
        $file_size = $file['size'];
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = $data['dateId'] . '_' . $data['doc_id'] . '_' . $data['app_id'] . '_' . $data['patient_id'] . '.' . $file_ext;
        $destination = $upload_dir . $file_name;

        // Validate file
        if (!in_array($file_type, $allowed_types)) {
            echo "Error: Only PDF, PNG, JPG, and JPEG files are allowed.";
            exit;
        }
        if ($file_size > $max_file_size) {
            echo "Error: File size exceeds 5MB limit.";
            exit;
        }

        // Move file to upload directory
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $error = error_get_last();
            echo "Error: Failed to upload file. " . ($error['message'] ?? 'Unknown error.');
            exit;
        }
        $file_path = 'assets/files/prescription_documents/' . $file_name;
    }

    $medicationDetails = [];
    foreach ($medications as $index => $med) {
        $medicationDetails[] = [
            'name' => $med,
            'dosage' => $dosages[$index] ?? '',
            'morning' => $morning[$index] ?? 0,
            'noon' => $noon[$index] ?? 0,
            'night' => $night[$index] ?? 0,
            'if_needed' => $if_needed[$index] ?? 0,
            'do_not_substitute' => isset($do_not_substitute[$index]) ? true : false,
        ];
    }

    $labTestDetails = [];
    foreach ($lab_tests as $index => $test) {
        $labTestDetails[] = [
            'name' => $test,
            'priority' => $priority[$index] ?? '',
        ];
    }

    if (!empty($medications)) {
        $medicalRecord = new MedicalRecord();
        $medicalRecord->insertRecord($remarks, $diagnosis, $patient_id, $file_name, $data['app_id']);
        $request_id = $medicalRecord->getLastInsertedId($patient_id,$data['app_id']);
        //print_r($request_id);

        foreach ($medicationDetails as $medic) {
            $medicalRecord->insertMed($medic, $request_id);
        }
    }

    if (!empty($lab_tests)) {
        $labTest = new LabTest();
        $labTest->insertRecord($patient_id, $data['app_id']);
        $request_id = $labTest->getLastInsertedId($patient_id, $data['app_id']);

        foreach ($labTestDetails as $lab) {
            $labTest->insertTest($lab, $request_id);
        }
    }

    $appointments = new Appointments;
        $appointments->endAppointment($data['app_id']);
}
?>

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
            <div class="dashboard-content">
                <label for="dr-name">Doctor's Name:</label>
                <p>Dr. <?php echo htmlspecialchars($_SESSION['USER']->first_name); ?> <?php echo htmlspecialchars($_SESSION['USER']->last_name); ?></p>
                <label for="date">Date:</label>
                <p><?php echo htmlspecialchars(date('Y-m-d')); ?></p>
                <form method="post" action="" enctype="multipart/form-data">
                    <label for="diagnosis">Diagnosis:</label>
                    <input type="text" name="diagnosis" placeholder="Gastritis" style="font-size: 17px;margin-bottom:10px;">
                    <!-- Remarks Section -->
                    <div class="remarks-section">
                        <h3>Remarks</h3>
                        <input id="additionalRemarks" type="text" name="remarks" placeholder="State remarks if there is any" style="font-size: 16px;">
                    </div>
                    <!-- File Upload Section -->
                    <div class="file-upload-section">
                        <h3>Upload Document</h3>
                        <div class="file-upload-wrapper">
                            <input type="file" name="document" accept="image/*,.pdf">
                        </div>
                    </div>
                    <h2>MEDICINES NEED TO BE GIVEN:</h2>
                    <table class="medication-table">
                        <thead>
                            <tr>
                                <th>Name of the Medication</th>
                                <th>Dosage of the Medication</th>
                                <th colspan="4">Number taken at a time</th>
                                <th>Allowed to substitute</th>
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
                        <tbody id="prescription-body">
                            <tr>
                                <td><input type="text" name="medication_name[]" placeholder="Medicine Name" list="medicine-suggestions"></td>
                                <td><input type="text" name="dosage[]" placeholder="Dosage"></td>
                                <td><input type="number" name="morning[]" min="0" placeholder="0" min='0'></td>
                                <td><input type="number" name="noon[]" min="0" placeholder="0"></td>
                                <td><input type="number" name="night[]" min="0" placeholder="0"></td>
                                <td><input type="number" name="if_needed[]" min="0" placeholder="0"></td>
                                <td><input type="checkbox" name="do_not_substitute[]"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="add-row-btn" onclick="addMedicationRow(event)" style="margin-bottom: 15px;">+ Add Another Medication</button>

                    <h2>LAB TESTS NEED TO BE TAKEN:</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Name of the Test</th>
                                <th>Priority Level</th>
                            </tr>
                        </thead>
                        <tbody id="prescription-body1">
                            <tr>
                                <td><input type="text" name="test_name[]" placeholder="Test Name"></td>
                                <td><input type="text" name="priority[]" placeholder="Priority"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="add-row-btn" onclick="addLabTestRow(event)" style="margin-bottom: 15px;">+ Add Another Test</button>

                    <div class="submit-btn-container">
                        <button type="submit" class="submit-btn" value="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Datalist for Medicine Suggestions -->
    <datalist id="medicine-suggestions">
        <?php foreach ($data['medicines'] as $medicine): ?>
            <option value="<?= htmlspecialchars($medicine->generic_name) ?>">
            <option value="<?= htmlspecialchars($medicine->brand_name) ?>">
        <?php endforeach; ?>
    </datalist>

    <script>
        function addMedicationRow(event) {
            event.preventDefault();
            const tableBody = document.getElementById('prescription-body');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="medication_name[]" placeholder="Medicine Name" list="medicine-suggestions"></td>
                <td><input type="text" name="dosage[]" placeholder="Dosage"></td>
                <td><input type="number" name="morning[]" min="0" placeholder="0"></td>
                <td><input type="number" name="noon[]" min="0" placeholder="0"></td>
                <td><input type="number" name="night[]" min="0" placeholder="0"></td>
                <td><input type="number" name="if_needed[]" min="0" placeholder="0"></td>
                <td><input type="checkbox" name="do_not_substitute[]"></td>
            `;
            tableBody.appendChild(newRow);
        }

        function addLabTestRow(event) {
            event.preventDefault();
            const tableBody = document.getElementById('prescription-body1');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" name="test_name[]" placeholder="Test Name"></td>
                <td><input type="text" name="priority[]" placeholder="Priority"></td>
            `;
            tableBody.appendChild(newRow);
        }
    </script>
</body>
</html>