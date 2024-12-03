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
                        <span class="form-title">Personal Information</span>
                        <div class="form-row">
                            <label for="fullName">Full Name:</label>
                            <input type="text" id="fullName" name="fullName">
                        </div>
                        
                        <div class="form-row">
                            <label for="dob">Date of Birth:</label>
                            <input type="date" id="dob" name="dob">
                            <label for="age" class="age-label">Age:</label>
                            <input type="text" id="age" name="age" class="age-input">
                        </div>
                        
                        <div class="form-row">
                            <label for="gender">Gender:</label>
                            <input type="radio" id="male" name="gender" value="M">
                            <label for="male">M</label>
                            <input type="radio" id="female" name="gender" value="F">
                            <label for="female">F</label>
                        </div>
                        
                        <div class="form-row">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address">
                        </div>
                        
                        <div class="form-row">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email">
                        </div>
                        
                        <div class="form-row">
                            <label for="contact">Contact No:</label>
                            <input type="text" id="contact" name="contact">
                            <label for="emergency-contact" class="emergency-label">Emergency Contact No:</label>
                            <input type="text" id="emergency-contact" name="emergency-contact" class="emergency-input">
                        </div>

                        <button type="submit" class="next-button"><a href="<?= ROOT ?>/Admin/pharmacistForm2">Next</a></button>
                        
                    </form>
                </div>
                
                
                
            </div>
                
        </div>
    </div>

</body>
</html>
