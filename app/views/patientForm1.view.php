<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/signup.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">

        <!-- Main Content -->

        <div class="form-container">

            <form id='patient-form1' class="patient-form" action="./signup" method="post">
                <div class="logo-container">
                    <img class="logo" src="<?= ROOT?>/assets/images/logo.png" />
                    <div class="logo_text">WELL BE</div>
                </div>
                <span class="form-title">Sign Up </br>Personal Information</span>

                <div class="form-row">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required
                        value="<?= $_SESSION['form1_data']['first_name'] ?? '' ?>">
                </div>
                <?php if (!empty($errors['first_name'])): ?>
                    <div class="input-error">
                        <?php echo $errors['first_name'] ?>
                    </div>
                <?php endif; ?>


                <div class="form-row">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required
                        value="<?= $_SESSION['form1_data']['last_name'] ?? '' ?>">
                </div>
                <?php if (!empty($errors['last_name'])): ?>
                    <div class="input-error">
                        <?php echo $errors['last_name'] ?>
                    </div>
                <?php endif; ?>

                <div class="form-row">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required
                        value="<?= $_SESSION['form1_data']['password'] ?? '' ?>">
                </div>

                <div class="form-row">
                    <label for="noc">NIC:</label>
                    <input type="text" id="nic" name="nic" required value="<?= $_SESSION['form1_data']['nic'] ?? '' ?>">
                </div>

                <div class="form-row">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required value="<?= $_SESSION['form1_data']['dob'] ?? '' ?>">


                </div>

                <div class="form-row">
                    <label for="gender">Gender:</label>
                    <input type="radio" id="male" name="gender" value="M" <?= (isset($_SESSION['form1_data']['gender']) && $_SESSION['form1_data']['gender'] === 'M') ? 'checked' : '' ?> required>
                    <label for="male">M</label>
                    <input type="radio" id="female" name="gender" value="F" <?= (isset($_SESSION['form1_data']['gender']) && $_SESSION['form1_data']['gender'] === 'F') ? 'checked' : '' ?>>
                    <label for="female">F</label>
                </div>


                <div class="form-row">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required
                        value="<?= $_SESSION['form1_data']['address'] ?? '' ?>">
                </div>

                <div class="form-row">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required
                        value="<?= $_SESSION['form1_data']['email'] ?? '' ?>">
                </div>

                <div class="form-row">
                    <label for="contact">Contact No:</label>
                    <input type="text" id="contact" name="contact" required
                        value="<?= $_SESSION['form1_data']['contact'] ?? '' ?>">
                </div>
                <?php if (!empty($errors['contact'])): ?>
                    <div class="input-error">
                        <?php echo $errors['contact'] ?>
                    </div>
                <?php endif; ?>

                <div class="buttons-bar">
                        <button type="button" class="prev-button" onclick="window.location.href='./'">Back</button>
                        <button type="submit" class="next-button" >Next</button>
                    </div>
            </form>

        </div>


    </div>
    <script src="signup-validation.js"></script>

</body>

</html>