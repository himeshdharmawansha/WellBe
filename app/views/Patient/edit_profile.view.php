
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/patient_dashboard.css?v=<?= time() ?>">
</head>
<body>
    <div class="big">
    <div class="edit-profile-container">
        <h1>Edit Profile</h1>
        <form method="POST" action="" onsubmit="return validateForm()">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" required>
    <span class="error-message" id="first_name_error"></span>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" >
    <span class="error-message" id="last_name_error"></span>

    <label for="contact">Contact:</label>
    <input type="text" name="contact" id="contact" pattern="\d{10}" title="Contact must be a 10-digit number.">
    <span class="error-message" id="contact_error"></span>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" >
    <span class="error-message" id="email_error"></span>

    <label for="address">Address:</label>
    <textarea name="address" id="address" required></textarea>
    <span class="error-message" id="address_error"></span>

    <label for="medical_history">Medical History:</label>
    <textarea name="medical_history" id="medical_history"></textarea>

    <label for="allergies">Allergies:</label>
    <textarea name="allergies" id="allergies"></textarea>

    <button type="submit" class="button">Save Changes</button>
    <button type="button" class="button" onclick="window.location.href='patient_dashboard'">Cancel</button>
</form>
<script>
    function validateForm() {
        let isValid = true;

        // First Name Validation
        const firstName = document.getElementById("first_name").value.trim();
        const firstNameError = document.getElementById("first_name_error");
        if (!firstName) {
            firstNameError.textContent = "First Name is required.";
            isValid = false;
        } else {
            firstNameError.textContent = "";
        }

        // Last Name Validation
        const lastName = document.getElementById("last_name").value.trim();
        const lastNameError = document.getElementById("last_name_error");
        if (!lastName) {
            lastNameError.textContent = "Last Name is required.";
            isValid = false;
        } else {
            lastNameError.textContent = "";
        }

        // Contact Validation
        const contact = document.getElementById("contact").value.trim();
        const contactError = document.getElementById("contact_error");
        const contactPattern = /^\d{10}$/;
        if (!contactPattern.test(contact)) {
            contactError.textContent = "Contact must be a 10-digit number.";
            isValid = false;
        } else {
            contactError.textContent = "";
        }

        // Email Validation
        const email = document.getElementById("email").value.trim();
        const emailError = document.getElementById("email_error");
        if (!email) {
            emailError.textContent = "Email is required.";
            isValid = false;
        } else {
            emailError.textContent = "";
        }

        // Address Validation
        const address = document.getElementById("address").value.trim();
        const addressError = document.getElementById("address_error");
        if (!address) {
            addressError.textContent = "Address is required.";
            isValid = false;
        } else {
            addressError.textContent = "";
        }

        return isValid;
    }
</script>

</body>
</html>
