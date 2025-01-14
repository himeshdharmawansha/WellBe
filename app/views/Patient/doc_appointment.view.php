<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/doc_appointment.css?v=<?= time() ?>">
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
            include $_SERVER['DOCUMENT_ROOT'] . '/WellBe/app/views/Components/Patient/header.php';
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
                        <form method="POST">
                            <!-- Doctor Selection -->
                            <div class="input-box">
                                <label for="doctor">Select Doctor</label>
                                <input list="doctors" id="doctor" name="doctor" placeholder="Type doctor name">
                                <datalist id="doctors">
                                    <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?= htmlspecialchars($doctor->name) ?>"></option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>

                            <!-- Specialization Selection -->
                            <div class="input-box">
                                <label for="specialization">Specialization</label>
                                <input list="specializations" id="specialization" name="specialization"
                                    placeholder="Type specialization">
                                <datalist id="specializations">
                                <?php foreach ($doctors as $doctor): ?>
                                        <option value="<?= htmlspecialchars($doctor->specialization); ?>">
                                        
                                        </option>
                                    <?php endforeach; ?>
                                </datalist>
                            </div>

                            <div class="input-box">
                                <label for="date">Select Date and Time</label>
                                <select id="date" name="date-input">
                                    <datalist id="date">
                                    </datalist>
                                </select>
                            </div>

                            <button class="find-doctor-btn" onclick="window.location.href='hello'">Book
                                Appointment</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>