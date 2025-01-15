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
                    
                    <form class="patient-form" action="<?php echo ROOT; ?>/Admin/doctorForm1" method="POST">
                        <span class="form-title">Personal Information</span>

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
                            <label for="nic">NIC:</label>
                            <input type="text" id="nic" name="nic" value="<?= htmlspecialchars($formData['nic'] ?? '') ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="dob">Date of Birth:</label>
                            <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($formData['dob'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="gender">Gender:</label>
                            <input type="radio" id="male" name="gender" value="M" 
                                <?= isset($formData['gender']) && $formData['gender'] === 'M' ? 'checked' : '' ?>>
                            <label for="male">M</label>

                            <input type="radio" id="female" name="gender" value="F" 
                                <?= isset($formData['gender']) && $formData['gender'] === 'F' ? 'checked' : '' ?>>
                            <label for="female">F</label>
                        </div>
                        
                        <div class="form-row">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" value="<?= htmlspecialchars($formData['address'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-row">
                            <label for="contact">Contact No:</label>
                            <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($formData['contact'] ?? '') ?>" required>
                            <label for="emergency-contact" class="emergency-label">Emergency Contact No:</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" class="emergency-input" value="<?= htmlspecialchars($formData['emergency_contact'] ?? '') ?>" required>
                        </div>

                        <div class="form-row">
                            <label for="emergency-relationship">Emergency Contact Relationship:</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="<?= htmlspecialchars($formData['emergency_contact_relationship'] ?? '') ?>" required>
                        </div>
                        
                        <button type="submit" class="next-button">Next</button>
                        
                    </form>
                </div>    
            </div>        
        </div>
    </div>
</body>
</html>
