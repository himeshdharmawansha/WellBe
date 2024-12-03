<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Your Doctor</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/Search_doc.css">
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
        <?php
            $pageTitle = "Appointments"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="welcome-message">
                    <p>Welcome Mr. K.S. Perera</p>
                </div>
                
                <div class="flex-container">
                    
                    <div class="form-section">
                        <div class="header">
                            <p class="header-title">Search Your Doctor</p>
                        </div>
                        <div class="input-box">
                            <label for="doctor">Select Doctor</label>
                            <select id="doctor">
                                <option value="dr-noo">Dr. Noo</option>
                                <option value="dr-doo">Dr. Doo</option>
                                <option value="dr-kee">Dr. Kee</option>
                            </select>
                            <i class="dropdown-icon">▼</i>
                        </div>
                        <div class="input-box">
                            <label for="specialties">Select Specialties</label>
                            <select id="specialties">
                                <option value="cardiology">Cardiology</option>
                                <option value="neurology">Neurology</option>
                                <option value="pediatrics">Pediatrics</option>
                            </select>
                            <i class="dropdown-icon">▼</i>
                        </div>
                        <div class="input-box">
                            <label for="date">Select Date</label>
                            <select id="date">
                                <option value="12-08-2024">12/08/2024</option>
                                <option value="13-08-2024">13/08/2024</option>
                                <option value="14-08-2024">14/08/2024</option>
                            </select>
                            <i class="dropdown-icon">▼</i>
                        </div>
                        <a href="#" class="services-link">What are the medical services we offer?</a>
                        <button class="find-doctor-btn" onclick="window.location.href='Appointment_schedule'">
                            <i class="icon">🔍</i> Find a Doctor
                        </button>
                    </div>
                    
                    <div class="image-container">
                        <img src="../assests/medical-team (1).png" alt="Doctor Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
