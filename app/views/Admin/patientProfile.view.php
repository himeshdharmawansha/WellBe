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
            $pageTitle = "Patient Profile Information"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
            <div class="patient-profile-container">
                <?php if (!empty($patientProfile)): ?>
                    <form class="patient-profile-form" method = "POST" action="<?php echo ROOT; ?>/Admin/patientProfile?nic=<?= $patientProfile->nic ?>">
                        <span class="profile-form-title">Personal Information</span>

                        <div class = "error-messages">
                            <?php if (!empty($errors)): ?>
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="profile-form-row">
                            <label for="nic">NIC:</label>
                            <input type="text" id="nic" name="nic" value="<?= htmlspecialchars($patientProfile->nic) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($patientProfile->first_name) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($patientProfile->last_name) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="dob">Date of Birth:</label>
                            <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($patientProfile->dob) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="gender">Gender:</label>
                            <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($patientProfile->gender) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" value="<?= htmlspecialchars($patientProfile->address) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($patientProfile->email) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="contact">Contact:</label>
                            <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($patientProfile->contact) ?>" readonly>
                        </div>    

                        <span class="profile-form-title">Health Information:</span>
                        <div class="profile-form-row">
                            <label for="medical_history">Medical History:</label>
                            <input type="text" id="medical_history" name="medical_history" value="<?= htmlspecialchars($patientProfile->medical_history) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="allergies">ALlergies:</label>
                            <input type="text" id="allergies" name="allergies" value="<?= htmlspecialchars($patientProfile->allergies) ?>" readonly>
                        </div>

                        <span class="profile-form-title">Emergency Contact Information:</span>
                        <div class="profile-form-row">
                            <label for="emergency_contact_name">Emergency Contact Name:</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= htmlspecialchars($patientProfile->emergency_contact_name) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="emergency_contact_no">Emergency Contact No:</label>
                            <input type="text" id="emergency_contact_no" name="emergency_contact_no" value="<?= htmlspecialchars($patientProfile->emergency_contact_no) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="emergency_contact_relationship">Emergency Contact Relationship:</label>
                            <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="<?= htmlspecialchars($patientProfile->emergency_contact_relationship) ?>" readonly>
                        </div>

                        <div class="profile-buttons-bar">
                            <button type="button" class="edit-button" onclick="toggleEditMode()">Edit</button>
                            <button type="submit" class="save-button" name="action" value="update" style="display: none;">Save</button>
                            <button type="submit" class="delete-button" name="action" value="delete" onClick="return confirmDeletion();">Delete</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p><?= htmlspecialchars($error ?? 'No patient details available.') ?></p>
                <?php endif; ?>

                <script>
                    function toggleEditMode() {
                        const form = document.querySelector('.patient-profile-form');
                        const inputs = form.querySelectorAll('input');
                        const editButton = document.querySelector('.edit-button');
                        const saveButton = document.querySelector('.save-button');

                        // Toggle the readonly attribute on all inputs
                        inputs.forEach(input => {
                            if (input.hasAttribute('readonly')) {
                                input.removeAttribute('readonly');
                            } else {
                                input.setAttribute('readonly', 'readonly');
                            }
                        });

                        // Toggle button visibility
                        if (editButton.style.display !== 'none') {
                            editButton.style.display = 'none';
                            saveButton.style.display = 'inline-block';
                        } else {
                            editButton.style.display = 'inline-block';
                            saveButton.style.display = 'none';
                        }
                    }

                    
                    function confirmDeletion() {
                        return confirm("Are you sure you want to delete this patient's profile?");
                    }

                </script>
                
            </div>        
        </div>
    </div>
</body>
</html>
