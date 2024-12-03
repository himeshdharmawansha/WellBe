<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/doctors.css">
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
            $pageTitle = "Doctors"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Admin/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="form-tabs">
                    <span class="tab active">Doctor Details</span>
                </div>
                <div class="form-container">
                    
                    <form class="patient-form" action="<?php echo ROOT; ?>/Admin/doctorForm2" method="POST">
                        <span class="form-title">Professional Information</span>
                        <div class="form-row">
                            <label for="medical history">Medical License Number:</label>
                            <input type="text" id="medical_license_no" name="medical_license_no">
                        </div>
                        
                        <div class="form-row">
                            <label for="specializtion">Specialization/ Field of Expertise:</label>
                            <input type="text" id="specialization" name="specialization">
                        </div>
                        
                        <div class="form-row">
                            <label for="experience">Years of Experience:</label>
                            <input type="text" id="experience" name="experience">
                        </div>
                        
                        <div class="form-row">
                            <label for="qualifications">Qualifications and Certifications:</label>
                            <input type="text" id="qualifications" name="qualifications">
                        </div>

                        <div class="form-row">
                            <label for="medical_school">Medical School/ University Attended:</label>
                            <input type="text" id="medical_school" name="medical_school">
                        </div>
                        
                        <div class="buttons-bar">
                            <button type="submit" class="prev-button">
                                <a href="<?= ROOT ?>/Admin/doctorForm1">Previous</a>
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
