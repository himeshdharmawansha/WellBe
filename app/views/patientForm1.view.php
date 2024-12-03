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
                    
                <form class="patient-form" action="<?= ROOT ?>/signup" method="post">
                    <span class="form-title">Personal Information</span>
                    
                    <div class="form-row">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required value="<?= $_SESSION['form1_data']['first_name'] ?? '' ?>">
                    </div>
                    <?php if(!empty($errors['first_name'])):?>
                    <div>
                        <?php echo $errors['first_name'] ?>
                    </div>
                    <?php endif;?>



                    <div class="form-row">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required value="<?= $_SESSION['form1_data']['last_name'] ?? '' ?>">
                    </div>

                    <div class="form-row">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required value="<?= $_SESSION['form1_data']['password'] ?? '' ?>">
                    </div>

                    <div class="form-row">
                        <label for="noc">NIC:</label>
                        <input type="text" id="nic" name="nic" required value="<?= $_SESSION['form1_data']['nic'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required value="<?= $_SESSION['form1_data']['dob'] ?? '' ?>">
                        <label for="age" class="age-label">Age:</label>
                        <input type="number" id="age" name="age" required class="age-input" value="<?= $_SESSION['form1_data']['age'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="gender">Gender:</label>
                        <input type="radio" id="male" name="gender" value="M" <?= (isset($_SESSION['form1_data']['gender']) && $_SESSION['form1_data']['gender'] === 'M') ? 'checked' : '' ?>>
                        <label for="male">M</label>
                        <input type="radio" id="female" name="gender"  value="F" <?= (isset($_SESSION['form1_data']['gender']) && $_SESSION['form1_data']['gender'] === 'F') ? 'checked' : '' ?>>
                        <label for="female">F</label>
                    </div>
                    
                    <div class="form-row">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required value="<?= $_SESSION['form1_data']['address'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" required value="<?= $_SESSION['form1_data']['email'] ?? '' ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="contact">Contact No:</label>
                        <input type="text" id="contact" name="contact" required value="<?= $_SESSION['form1_data']['contact'] ?? '' ?>">
                    </div>
                    
                    <button type="submit" class="next-button">Next</button>
                </form>

                </div>    
            </div>        
        </div>
    </div>

</body>
</html>
