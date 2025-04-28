<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Queue</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/appointments.css">
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
            include $_SERVER['DOCUMENT_ROOT'] . '/wellbe/app/views/Components/Receptionist/header.php';
            ?>

            <?php $session = $data['session']; ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="top-buttons">
                    <!-- <div class="session-title">
                        <span class="session">29/11/2024  11:00-13:00  Dr. Nishantha Samarasekera</span>
                    </div> -->
                    <span class="session-title">
                        <?= date('d/m/Y', strtotime($session->date)) ?>  
                        <?= date('H:i', strtotime($session->start_time)) ?> - 
                        <?= date('H:i', strtotime($session->end_time)) ?>  
                        Dr. <?= $session->doctor_name ?>
                    </span>
                    <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Search by Appointment No" />
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="view-buttons">
                    <span class="ongoing active">Patient List</span>
                </div>

                <form method="POST" action="<?php echo ROOT; ?>/Receptionist/appointmentQueue">
                    <div class="table-container">
                        <table class="queue-table">
                            <tr class="header-row" >
                                <th>Appointment No</th>
                                <th>Patient Name</th>
                                <th>Verified</th>
                                <th>Patient Status</th>
                                <th>Payment Status</th>
                            </tr>

                            <?php if (!empty($appointments)): ?>
                                <?php foreach ($appointments as $index => $app): ?>
                                    <tr class="data-row">
                                        <td><?= htmlspecialchars($app->appointment_id) ?></td>
                                        <td><?= htmlspecialchars($app->patient_name) ?></td>

                                        <!-- Hidden inputs to track appointment ID and original statuses -->
                                        <input type="hidden" name="slot_id" value="<?= htmlspecialchars($_GET['slot_id'] ?? '') ?>">
                                        <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($_GET['doctor_id'] ?? '') ?>">
                                        <input type="hidden" name="appointments[<?= $index ?>][appointment_id]" value="<?= $app->appointment_id ?>">
                                        <input type="hidden" name="appointments[<?= $index ?>][original_patient_status]" value="<?= $app->patient_status ?>">
                                        <input type="hidden" name="appointments[<?= $index ?>][original_payment_status]" value="<?= $app->payment_status ?>">
                                        <input type="hidden" name="appointments[<?= $index ?>][patient_id]" value="<?= $app->patient_id ?>">
                                        <input type="hidden" name="appointments[<?= $index ?>][original_verified]" value="<?= $app->verified ?>">
                                        <input type="hidden" name="appointments[<?= $index ?>][patient_type]" value="<?= $app->patient_type ?>">

                                        <td>
                                            <?php if ($app->verified === 'Verified'): ?>
                                                <!-- Already Verified patients: checkbox is checked and disabled -->
                                                <input type="checkbox" checked disabled>
                                                <input type="hidden" name="appointments[<?= $index ?>][verified]" value="Verified">
                                            <?php else: ?>
                                                <!-- Not Verified patients: allow checking -->
                                                <input type="checkbox" name="appointments[<?= $index ?>][verified]" value="Verified">
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <input type="hidden" name="appointments[<?= $index ?>][patient_status]" value="<?= $app->patient_status ?>" class="hidden-patient-status">
                                            
                                            <select class="patient-status-dropdown" name="appointments[<?= $index ?>][patient_status]" 
                                            onchange="changeDropdownColor(this); syncToHidden(this);" 
                                            style="<?= $app->patient_status === 'PRESENT' ? 'background-color: #24FF3A; color: black;' : 'background-color: #EFF4FF; color: #FF4747;' ?>"
                                            <?= $app->patient_status === 'PRESENT' ? 'disabled' : '' ?>>
                                                
                                                <option value="NOT PRESENT" <?= $app->patient_status === 'NOT PRESENT' ? 'selected' : '' ?>>Not Present</option>
                                                <option value="PRESENT" <?= $app->patient_status === 'PRESENT' ? 'selected' : '' ?>>Present</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="appointments[<?= $index ?>][payment_status]" value="<?= $app->payment_status ?>" class="hidden-payment-status">
                                            
                                            <select class="patient-status-dropdown" name="appointments[<?= $index ?>][payment_status]" 
                                            onchange="changeDropdownColor(this); syncToHidden(this);" 
                                            style="<?= $app->payment_status === 'Paid' ? 'background-color: #24FF3A; color: black;' : 'background-color: #EFF4FF; color: #FF4747;' ?>"
                                            <?= $app->payment_status === 'Paid' ? 'disabled' : '' ?>>
                                            
                                                <option value="Not Paid" <?= $app->payment_status === 'Not Paid' ? 'selected' : '' ?>>Not Paid</option>
                                                <option value="Paid" <?= $app->payment_status === 'Paid' ? 'selected' : '' ?>>Paid</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No appointments found.</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class = "button-bar">
                        <button type = "submit" class = "update-button">Update</button>
                    </div>
                </form>
            </div>        
        </div>
    </div>

    <script>
        function changeDropdownColor(dropdown) {
            if (dropdown.value === "PRESENT" || dropdown.value === "Paid") {
                dropdown.style.backgroundColor = "#24FF3A";
                dropdown.style.color = "black"; // Optional, to make the text readable
            } else {
                dropdown.style.backgroundColor = "#EFF4FF";
                dropdown.style.color = "#FF4747"; // Reset to default styles
            }
        }

        function syncToHidden(select) {
            const td = select.closest('td');
            const hidden = td.querySelector('input[type="hidden"]');
            if (hidden) {
                hidden.value = select.value;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(filter)) {
                        row.style.display = ''; 
                    } else {
                        row.style.display = 'none'; 
                    }
                });
            });
        });
    </script>

</body>
</html>
