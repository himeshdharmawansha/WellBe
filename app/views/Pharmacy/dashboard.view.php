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
                    <div id="countdown">
                        <div class="time-unit">
                            <span id="hours">00</span>
                            <p>HOURS</p>
                        </div>
                        <span class="inline-separator">:</span>
                        <div class="time-unit">
                            <span id="minutes">00</span>
                            <p>MINS</p>
                        </div>
                        <span class="inline-separator">:</span>
                        <div class="time-unit">
                            <span id="seconds">00</span>
                            <p>SEC</p>
                        </div>
                    </div>

                    <div class="cards-container">
                        <!-- Statistics Cards -->
                        <div class="card new-request" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fa-solid icon fa-hourglass-start"></i>
                            </span>
                            <p>000 <br>New_Requests</p>
                        </div>
                        <div class="card ongoing" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-pills"></i>
                            </span>
                            <p>000 <br>In_progress</p>
                        </div>
                        <div class="card completed" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-tasks"></i>
                            </span>
                            <p>000 <br> Completed</p>
                        </div>
                    </div>
                </div>
                <div class="content-container">
                    <div class="dashboard messages">

                        <div class="header">
                            <h3>Medication Requests</h3>
                            <a href="requests" class="see-all">See all</a>
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
                            <a href="chat" class="see-all">See all</a>
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
        document.addEventListener("DOMContentLoaded", function() {
            google.charts.load('current', {
                packages: ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                fetch('<?= ROOT ?>/Pharmacy/getRequestsByDay')
                    .then(response => response.json())
                    .then(data => {
                        // Prepare data for the chart
                        const chartData = [
                            ['Day', 'Given'],
                            ['M', data[0]],
                            ['T', data[1]],
                            ['W', data[2]],
                            ['T', data[3]],
                            ['F', data[4]],
                            ['S', data[5]],
                            ['S', data[6]],
                        ];

                        const options = {
                            title: 'Medication Requests',
                            curveType: 'function',
                            legend: {
                                position: 'bottom'
                            },
                        };

                        const chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                        chart.draw(google.visualization.arrayToDataTable(chartData), options);
                    })
                    .catch(error => console.error('Error fetching chart data:', error));
            }
        });



        function startCountdown(duration) {
            const countdown = {
                hours: document.getElementById("hours"),
                minutes: document.getElementById("minutes"),
                seconds: document.getElementById("seconds"),
            };

            let timer = duration,
                hours, minutes, seconds;

            setInterval(function() {
                hours = Math.floor(timer / 3600);
                minutes = Math.floor((timer % 3600) / 60);
                seconds = timer % 60;

                countdown.hours.textContent = String(hours).padStart(2, '0');
                countdown.minutes.textContent = String(minutes).padStart(2, '0');
                countdown.seconds.textContent = String(seconds).padStart(2, '0');

                if (--timer < 0) {
                    timer = 0; // Reset timer if it reaches zero.
                }
            }, 1000);
        }

        // Initialize the countdown with a duration in seconds (e.g., 15 hours).
        startCountdown(15 * 3600);

        document.addEventListener("DOMContentLoaded", function() {
            function updateRequestCounts() {
                fetch('<?= ROOT ?>/Pharmacy/getRequestCounts')
                    .then(response => response.json())
                    .then(data => {
                        // Update the UI with fetched data
                        document.querySelector('.new-request p').innerHTML = `${data.pending} <br> New_Requests`;
                        document.querySelector('.ongoing p').innerHTML = `${data.progress} <br> In_progress`;
                        document.querySelector('.completed p').innerHTML = `${data.completed} <br> Completed`;
                    })
                    .catch(error => console.error('Error fetching request counts:', error));
            }

            // Call the function on page load
            updateRequestCounts();

            // Optionally, refresh every 5 seconds
            setInterval(updateRequestCounts, 1000);
        });
    </script>
</body>

</html>