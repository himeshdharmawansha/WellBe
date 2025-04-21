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
            $pageTitle = "Pharmacist Profile Information"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
            <div class="pharmacist-profile-container">
                <?php if (!empty($pharmacistProfile)): ?>
                    <form class="pharmacist-profile-form" method = "POST" action="<?php echo ROOT; ?>/Admin/pharmacistProfile?nic=<?= $pharmacistProfile->nic ?>">
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

                        <!-- <div class="profile-form-row">
                            <label for="nic">NIC:</label>
                            <input type="text" id="nic" name="nic" value="<?= htmlspecialchars($pharmacistProfile->nic) ?>" readonly>
                        </div> -->
                        <div class="profile-form-row">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($pharmacistProfile->first_name) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($pharmacistProfile->last_name) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="dob">Date of Birth:</label>
                            <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($pharmacistProfile->dob) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="gender">Gender:</label>
                            <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($pharmacistProfile->gender) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" value="<?= htmlspecialchars($pharmacistProfile->address) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($pharmacistProfile->email) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="contact">Contact:</label>
                            <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($pharmacistProfile->contact) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="emergency_contact_no">Emergency Contact:</label>
                            <input type="text" id="emergency_contact_no" name="emergency_contact_no" value="<?= htmlspecialchars($pharmacistProfile->emergency_contact_no) ?>" readonly>
                        </div>

                        <span class="profile-form-title">Professional Information:</span>
                        <div class="profile-form-row">
                            <label for="medical_license_no">Medical License No:</label>
                            <input type="text" id="medical_license_no" name="medical_license_no" value="<?= htmlspecialchars($pharmacistProfile->medical_license_no) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="experience">Experience (Years):</label>
                            <input type="text" id="experience" name="experience" value="<?= htmlspecialchars($pharmacistProfile->experience) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="qualifications">Qualifications:</label>
                            <input type="text" id="qualifications" name="qualifications" value="<?= htmlspecialchars($pharmacistProfile->qualifications) ?>" readonly>
                        </div>
                        <div class="profile-form-row">
                            <label for="medical_school">Previous Employment History:</label>
                            <input type="text" id="prev_employment_history" name="prev_employment_history" value="<?= htmlspecialchars($pharmacistProfile->prev_employment_history) ?>" readonly>
                        </div>

                        <div class="profile-buttons-bar">
                            <button type="button" class="edit-button" onclick="toggleEditMode()">Edit</button>
                            <button type="submit" class="save-button" name="action" value="update" style="display: none;">Save</button>
                            <button type="submit" class="delete-button" name="action" value="delete" onClick="return confirmDeletion();">Delete</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p><?= htmlspecialchars($error ?? 'No pharmacist details available.') ?></p>
                <?php endif; ?>

                <script>
                    function toggleEditMode() {
                        const form = document.querySelector('.pharmacist-profile-form');
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
                        return confirm("Are you sure you want to delete this doctor's profile?");
                    }

                </script>
                
            </div>        
        </div>
    </div>
</body>
</html>
