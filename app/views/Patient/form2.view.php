
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
    <link rel="stylesheet" href="./signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">


                <div class="form-container">
                    
                <form id='patient-form2' class="patient-form" action="./signup" method="post">
                <div class="logo-container">
                    <img class="logo" src="./assets/logo.png" />
                    <div class="logo_text">WELL BE</div>
                </div>
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
                        <button type="button" class="prev-button" onclick="window.location.href='./form1.php';">Previous</button>
                        <button type="submit" class="submit-button">Submit</button>
                    </div>
                </form>

                </div>
                
                
                
            </div>
                
        </div>
    </div>
    <script src="signup-validation.js"></script>

</body>
</html>
