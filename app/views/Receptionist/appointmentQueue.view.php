<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Staff Dashboard</title>
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
            include $_SERVER['DOCUMENT_ROOT'] . '/mvc/app/views/Components/Receptionist/header.php';
            ?>

            <!--Content Container-->
            <div class="content-container">
                <div class="top-buttons">
                    <!-- <div class="session-title">
                        <span class="session">29/11/2024  11:00-13:00  Dr. Nishantha Samarasekera</span>
                    </div> -->
                    <span class="session-title">29/11/2024  11:00-13:00  Dr. Nishantha Samarasekera</span>
                    <div class="search-bar">
                        <input type="text" placeholder="Search by Appointment No" />
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="view-buttons">
                    <span class="ongoing active">Patient List</span>
                </div>

                <div class="table-container">
                    <table class="queue-table">
                        <tr class="header-row" >
                            <th>Appointment No</th>
                            <th>Patient Name</th>
                            <th>Booked Date</th>
                            <th>Booked Time</th>
                            <th>Patient Status</th>
                        </tr>
                        <tr class="data-row">
                            <td>01</td>
                            <td>John Doe</td>
                            <td>25/11/2024</td>
                            <td>15:00</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>02</td>
                            <td>Sandaruwani Peiris</td>
                            <td>25/11/2024</td>
                            <td>17:00</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>03</td>
                            <td>Dave Franco</td>
                            <td>26/11/2024</td>
                            <td>11:00</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>04</td>
                            <td>Lisa Perera</td>
                            <td>27/11/2024</td>
                            <td>12:30</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>05</td>
                            <td>Jane Fernando</td>
                            <td>28/11/2024</td>
                            <td>09:00</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>06</td>
                            <td>Hailey Silva</td>
                            <td>28/11/2024</td>
                            <td>13:20</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>07</td>
                            <td>Niall Perera</td>
                            <td>28/11/2024</td>
                            <td>14:10</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="data-row">
                            <td>08</td>
                            <td>Liam Payne</td>
                            <td>28/11/2024</td>
                            <td>17:00</td>
                            <td>
                                <select name="patient_status" class="patient-status-dropdown" onchange="changeDropdownColor(this)">
                                    <option value="Not Present" selected>Not Present</option>
                                    <option value="Present">Present</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>        
        </div>
    </div>

    <script>
        function changeDropdownColor(dropdown) {
            if (dropdown.value === "Present") {
                dropdown.style.backgroundColor = "#24FF3A";
                dropdown.style.color = "black"; // Optional, to make the text readable
            } else {
                dropdown.style.backgroundColor = "#EFF4FF";
                dropdown.style.color = "#FF4747"; // Reset to default styles
            }
        }
    </script>

</body>
</html>
