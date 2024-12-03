<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/phamacistDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
            $pageTitle = "Dashboard"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Lab/header.php';
            ?>
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="welcome-message">
                    <h4 class="welcome">Welcome Mr. K.S.Perera</h4>
                    <h4 class="date">10 August, 2024</h4>
                </div>
                <div class="topbar">
                    <div class="search-bar">
                        <input type="text" placeholder="Search by Patient ID" />
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <div class="cards-container">
                        <!-- Statistics Cards -->
                        <div class="card new-request" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fa-solid icon fa-hourglass-start"></i>
                            </span>
                            <p>120 <br>New_Requests</p>
                        </div>
                        <div class="card ongoing" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-pills"></i>
                            </span>
                            <p>25 <br>In_progress</p>
                        </div>
                        <div class="card completed" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-tasks"></i>
                            </span>
                            <p>34 <br> Completed</p>
                        </div>
                    </div>
                </div>
                <div class="content-container">
                    <div class="dashboard messages">

                        <div class="header">
                            <h3>Medication Requests</h3>
                            <a href="#" class="see-all">See all</a>
                        </div>
                        <table class="request-table">
                            <tr>
                                <th style="padding-right: 140px;">Patient_ID</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <td>pID_123432</td>
                                <td><span class="status progress">progress</span></td>
                            </tr>
                            <tr>
                                <td>pID_124562</td>
                                <td><span class="status progress">progress</span></td>
                            </tr>
                            <tr>
                                <td>pID_123782</td>
                                <td><span class="status pending">pending</span></td>
                            </tr>
                            <tr>
                                <td>pID_123472</td>
                                <td><span class="status pending">pending</span></td>
                            </tr>
                            <tr>
                                <td>pID_123430</td>
                                <td><span class="status pending">pending</span></td>
                            </tr>

                        </table>
                    </div>
                    <div class="dashboard messages">
                        <div class="header">
                            <h3>New Messages</h3>
                            <a href="#" class="see-all">See all</a>
                        </div>
                        <table class="request-table">
                            <tr>
                                <th>Name</th>
                                <th>Time</th>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>
                            <tr>
                                <td>Mr. K.G. Gunawardana</td>
                                <td>3:30 pm</td>
                            </tr>

                        </table>
                    </div>
                    <div class="dashboard calendar-container">
                        <div id="curve_chart" style="width: 400px; height: 400px; padding:0%;margin:0%"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="<?= ROOT ?>/assets/js/Pharmacy/phamacistDashboard.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Given'],
                ['1', 43],
                ['2', 30],
                ['3', 16],
                ['4', 45],
                ['5', 29],
                ['6', 11],
                ['7', 39],

            ]);

            var options = {
                title: 'Medication Performance',
                curveType: 'function',
                legend: {
                    position: 'bottom'
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    </script>
</body>

</html>