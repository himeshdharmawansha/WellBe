<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/labTechs.css">
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
            $pageTitle = "Lab Technicians"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="form-tabs">
                    <span class="tab active">Lab Technician Details</span>
                </div>
                <div class="form-container">
                    
                    <form class="labtech-form" action="<?php echo ROOT; ?>/Admin/labTechForm2" method="POST">
                        <span class="form-title">Professional Information</span>
                        <div class="form-row">
                            <label for="medical history">Medical License Number:</label>
                            <input type="text" id="medical_license_no" name="medical_license_no" value="<?= htmlspecialchars($formData['medical_license_no'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="specializtion">Specialization/ Areas of Expertise:</label>
                            <input type="text" id="specialization" name="specialization" value="<?= htmlspecialchars($formData['specialization'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="experience">Years of Experience:</label>
                            <input type="text" id="experience" name="experience" value="<?= htmlspecialchars($formData['experience'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="qualifications">Qualifications and Certifications:</label>
                            <input type="text" id="qualifications" name="qualifications" value="<?= htmlspecialchars($formData['qualifications'] ?? '') ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="university">Previous Employment History:</label>
                            <input type="text" id="prev_employment_history" name="prev_employment_history" value="<?= htmlspecialchars($formData['prev_employment_history'] ?? '') ?>" required>
                        </div>
                        
                        <div class="buttons-bar">
                            <button type="submit" class="prev-button">
                                <a href="<?= ROOT ?>/Admin/labTechForm1">Previous</a>
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
