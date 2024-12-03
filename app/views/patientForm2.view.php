<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/patients.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Header -->
            <?php
            $pageTitle = "Patients"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/test/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="form-tabs">
                    <span class="tab active">User Details</span>
                </div>
                <div class="form-container">
                    
                <form class="patient-form" action="<?= ROOT ?>/signup/form2" method="post">
                    <span class="form-title">Health Information</span>
                    
                    <div class="form-row">
                        <label for="medical_history">Medical History (Past illnesses, Surgeries):</label>
                        <input type="text" id="medical_history" name="medical_history" value="<?= $_POST['medical_history'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="allergies">Allergies:</label>
                        <input type="text" id="allergies" name="allergies" value="<?= $_POST['allergies'] ?? '' ?>">
                    </div>
                    
                    <span class="form-title">Emergency Contact Information</span>
                    
                    <div class="form-row">
                        <label for="emergency_contact_name">Emergency Contact Name:</label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" required value="<?= $_POST['emergency_contact_name'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="emergency_contact_no">Emergency Contact No:</label>
                        <input type="text" id="emergency_contact_no" name="emergency_contact_no" required value="<?= $_POST['emergency_contact_no'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="emergency_contact_relationship">Emergency Contact Relationship:</label>
                        <input type="text" id="emergency_contact_relationship" required name="emergency_contact_relationship" value="<?= $_POST['emergency_contact_relationship'] ?? '' ?>">
                    </div>
                    
                    <div class="buttons-bar">
                        <button type="button" class="prev-button" onclick="window.location.href='<?= ROOT ?>/signup';">Previous</button>
                        <button type="submit" class="submit-button">Submit</button>
                    </div>
                </form>

                </div>
                
                
                
            </div>
                
        </div>
    </div>

</body>
</html>
