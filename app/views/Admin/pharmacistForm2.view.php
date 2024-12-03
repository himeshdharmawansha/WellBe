<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/pharmacists.css">
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
            $pageTitle = "Pharmacists"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Admin/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="form-tabs">
                    <span class="tab active">Pharmacist Details</span>
                </div>
                <div class="form-container">
                    
                    <form class="patient-form">
                        <span class="form-title">Professional Information</span>
                        <div class="form-row">
                            <label for="medical history">Medical License Number:</label>
                            <input type="text" id="medical license" name="medical license">
                        </div>
                        
                        <div class="form-row">
                            <label for="specializtion">Specialization/ Areas of Expertise:</label>
                            <input type="text" id="specializtion" name="specializtion">
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
                            <label for="university">Previous Employment History:</label>
                            <input type="text" id="university" name="university">
                        </div>
                        
                        <div class="buttons-bar">
                            <button type="submit" class="prev-button">
                                <a href="<?= ROOT ?>/Admin/pharmacistForm1">Previous</a>
                            </button>
                            <button type="submit" class="submit-button">
                                <a href="index.html">Submit</a>
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
