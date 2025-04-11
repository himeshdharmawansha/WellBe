<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/patients.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php
        $this->renderComponent('navbar', $active);
        ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Patients"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="form-tabs">
                    <span class="tab active">User Details</span>
                </div>
                <div class="form-container">
                    
                    <form class="patient-form" action="<?php echo ROOT; ?>/Admin/patientForm2" method="POST">
                        <span class="form-title">Health Information</span>

                        <div class = "error-messages">
                            <?php if (!empty($errors)): ?>
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="form-row">
                            <label for="medical history">Medical History (Past illnesses, Surgeries):</label>
                            <input type="text" id="medical_history" name="medical_history" value="<?= htmlspecialchars($formData['medical_history'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="allergies">Allergies:</label>
                            <input type="text" id="allergies" name="allergies" value="<?= htmlspecialchars($formData['allergies'] ?? '') ?>" required>
                        </div>

                        <span class="form-title">Emergency Contact Information</span>
                        
                        <div class="form-row">
                            <label for="emergency name">Emergency Contact Name:</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= htmlspecialchars($formData['emergency_contact_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="emergency no">Emergency Contact No:</label>
                            <input type="text" id="emergency_contact_no" name="emergency_contact_no" value="<?= htmlspecialchars($formData['emergency_contact_no'] ?? '') ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="emergency relationship">Emergency Contact Relationship:</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="<?= htmlspecialchars($formData['emergency_contact_relationship'] ?? '') ?>" required>
                        </div>
                        
                        <div class="buttons-bar">
                            <button type="submit" class="prev-button">
                                <a href="<?= ROOT ?>/Admin/patientForm1">Previous</a>
                            </button>
                            <button type="submit" class="submit-button">Submit</button>
                        </div>
                        
                    </form>
                </div>
                
                
                
            </div>
                
        </div>
    </div>

</body>
</html>
