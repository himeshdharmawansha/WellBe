<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details Collection</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/After_app.css">
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
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

            <div class="container">
                <!-- Channel Details Section -->
                <div class="channel-details">
                    <h2>Channel Details</h2>
                    <p><strong>Doctor Name :</strong> DR CAB MAKULOLUWA</p>
                    <br/>
                    <p><strong>Specialization :</strong> Eye Surgeon</p>
                    <br/>
                    <p><strong>Date :</strong> 2024 Aug 06</p>
                    <br/>
                    <p><strong>Time :</strong> 08:00 (24 hrs)</p>
                    <br/>
                    <p><strong>Appointment No :</strong> 19</p>
                    <br/>
                    <p><strong>Appointment Fees :</strong> Rs. 2500</p>
                    <br/>
                    <p><strong>Doctor Notes :</strong></p>
                </div>
        
                <!-- Patient Details Section -->
                <div class="patient-details">
                    <h2 class="title">Patient Details</h2>
                    <form action="#" method="POST">
                        <label for="full-name">Full Name:</label>
                        <input type="text" id="full-name" name="full-name" required>
                        
                        <label>Nationality:</label>
                        <div class="radio-group">
                            <input type="radio" id="sri-lankan" name="nationality" value="Sri Lankan" checked>
                            <label for="sri-lankan">Sri Lankan</label>
                            
                            <input type="radio" id="foreign" name="nationality" value="Foreign">
                            <label for="foreign">Foreign</label>
                        </div>
        
                        <label for="national-id">National ID No. (Mandatory for Sri Lankan):</label>
                        <input type="text" id="national-id" name="national-id">
        
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email">
        
                        <label for="contact">Contact No. (Mandatory):</label>
                        <input type="text" id="contact" name="contact" required>
        
                        <label for="emergency-contact">Emergency Contact No.:</label>
                        <input type="text" id="emergency-contact" name="emergency-contact">

                        <div class="checkbox">
                            <input type="checkbox" id="save-records" name="save-records">
                            
                            <label for="save-records">Save the Medical records in your profile</label>
                        </div>
                
                        <button type="submit" class="submit-btn" onclick="window.location.href='Checkout'">NEXT</button>
                        <br/>
                        <br/>

                    </form>
                </div>
            </div>
        
</body>
</html>
