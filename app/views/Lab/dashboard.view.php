<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Lab/labTechnicianDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
    <div class="dashboard-container">
        <?php
        $this->renderComponent('navbar', $active);
        ?>
        <div class="main-content">
            <?php
            $pageTitle = "Dashboard";
            include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
            ?>
            <div class="dashboard-content">
                <div class="welcome-message">
                    <h4 class="welcome">Welcome <?= $_SESSION['USER']->first_name ?></h4>
                    <h4 class="date"><?php echo date('j M, Y'); ?></h4>
                </div>
                <div class="topbar">
                    <div class="cards-container">
                        <div class="card new-request" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fa-solid icon fa-hourglass-start"></i>
                            </span>
                            <p>000<br>New_Requests</p>
                        </div>
                        <div class="card ongoing" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fa-solid icon fa-microscope"></i>
                            </span>
                            <p>000<br>In_progress</p>
                        </div>
                        <div class="card completed" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-tasks"></i>
                            </span>
                            <p>000<br> Completed</p>
                        </div>
                    </div>
                </div>
                <div class="content-container">
                    <div class="dashboard messages">
                        <div class="header">
                            <h3>Ongoing Tests</h3>
                            <a href="requests" class="see-all">See all</a>
                        </div>
                        <div class="table-container">
                            <table class="request-table">
                                <thead>
                                    <tr>
                                        <th style="padding-right: 140px;">Patient_ID</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="dashboard messages">
                        <div class="header">
                            <h3>New Messages</h3>
                            <a href="chat" class="see-all">See all</a>
                        </div>
                        <div class="table-container">
                            <table class="message-table">
                                <thead>
                                    <tr>
                                        <th style="padding-right: 240px;">Name</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="dashboard calendar-container">
                        <div id="curve_chart" style="width: 400px; height: 400px; padding:0%;margin:0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= ROOT ?>/assets/js/Lab/labTechnicianDashboard.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                fetch('<?= ROOT ?>/Lab/getRequestsByDay')
                    .then(response => response.json())
                    .then(data => {
                        const chartData = [
                            ['Day', 'Tested'],
                            ['M', data[0]],
                            ['T', data[1]],
                            ['W', data[2]],
                            ['T', data[3]],
                            ['F', data[4]],
                            ['S', data[5]],
                            ['S', data[6]],
                        ];

                        const options = {
                            title: 'Test Requests',
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

        document.addEventListener("DOMContentLoaded", function() {
            function updateRequestCounts() {
                fetch('<?= ROOT ?>/Lab/getRequestCounts')
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('.new-request p').innerHTML = `${data.pending} <br> New_Requests`;
                        document.querySelector('.ongoing p').innerHTML = `${data.ongoing} <br> In_progress`;
                        document.querySelector('.completed p').innerHTML = `${data.completed} <br> Completed`;
                    })
                    .catch(error => console.error('Error fetching request counts:', error));
            }

            updateRequestCounts();
            setInterval(updateRequestCounts, 1000);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('.request-table tbody');

            function fetchTestRequests() {
                fetch('<?= ROOT ?>/Lab/testRequests')
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        data.forEach(request => {
                            html += `
                        <tr>
                            <td>${request.patient_id}</td>
                            <td><span class="status ${request.state}">${request.state}</span></td>
                        </tr>
                    `;
                        });
                        tableBody.innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            }

            fetchTestRequests();
            setInterval(fetchTestRequests, 3000);
        });

        function formatTimeToAmPm(time) {
            const [hours, minutes, seconds] = time.split(':');
            const date = new Date();
            date.setHours(hours, minutes, seconds || 0);
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('.message-table tbody');
            const noMessagesRow = `
        <tr>
            <td colspan="2" style="text-align: center;">No new messages</td>
        </tr>
    `;

            function fetchNewMessages() {
                fetch('<?= ROOT ?>/Lab/fetchNewMessages')
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = noMessagesRow;
                        } else {
                            data.forEach(message => {
                                const time = new Date(message.last_message_date).toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                html += `
                            <tr>
                                <td>${message.first_name}</td>
                                <td>${formatTimeToAmPm(time)}</td>
                            </tr>
                        `;
                            });
                        }
                        tableBody.innerHTML = html;
                    })
                    .catch(error => console.error('Error fetching messages:', error));
            }

            fetchNewMessages();
            setInterval(fetchNewMessages, 5000);
        });

        function updateReceivedState() {
            fetch('<?= ROOT ?>/ChatController/loggedin')
                .catch(error => console.error("Error in loggedin :", error));
        }

        setInterval(updateReceivedState, 3000);
    </script>
</body>

</html>