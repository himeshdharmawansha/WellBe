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
                            <p><?= esc($data['counts']['pending']) ?><br>New_Requests</p>
                        </div>
                        <div class="card ongoing" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fa-solid icon fa-microscope"></i>
                            </span>
                            <p><?= esc($data['counts']['ongoing']) ?><br>In_progress</p>
                        </div>
                        <div class="card completed" onclick="window.location.href='requests'">
                            <span class="circle-background">
                                <i class="fas icon fa-tasks"></i>
                            </span>
                            <p><?= esc($data['counts']['completed']) ?><br>Completed</p>
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
                                    <tr>
                                        <td colspan="2">Loading...</td>
                                    </tr>
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
                                    <tr>
                                        <td colspan="2">Loading...</td>
                                    </tr>
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
        <!-- Error/Success Popup -->
        <div class="popup" id="error-popup">
            <span class="close-btn" onclick="closePopup()">×</span>
            <span id="popup-message"></span>
            <button class="retry-btn" id="retry-btn" style="display:none;">Retry</button>
        </div>
    </div>
    <script src="<?= ROOT ?>/assets/js/Lab/labTechnicianDashboard.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            let retryCallback = null;

            function showPopup(message, type = 'error', retry = false, callback = null) {
                console.log('showPopup called with message:', message, 'type:', type);
                const popup = document.getElementById('error-popup');
                const popupMessage = document.getElementById('popup-message');
                const retryBtn = document.getElementById('retry-btn');
                if (!popup || !popupMessage || !retryBtn) {
                    console.error('Popup elements not found:', { popup, popupMessage, retryBtn });
                    alert(message);
                    return;
                }
                popupMessage.textContent = message;
                popup.className = `popup ${type} active`;
                console.log('Popup class set to:', popup.className);

                if (retry) {
                    retryBtn.style.display = 'inline-block';
                    retryCallback = callback;
                } else {
                    retryBtn.style.display = 'none';
                    retryCallback = null;
                }

                setTimeout(() => {
                    popup.className = 'popup';
                    console.log('Popup hidden after timeout');
                }, 5000);
            }

            function closePopup() {
                console.log('closePopup called');
                const popup = document.getElementById('error-popup');
                if (popup) {
                    popup.className = 'popup';
                    console.log('Popup class reset to:', popup.className);
                }
            }

            function retryAction() {
                if (retryCallback) {
                    retryCallback();
                }
            }

            document.getElementById('retry-btn')?.addEventListener('click', retryAction);

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
                    .catch(error => {
                        showPopup('Error fetching chart data. Please try again', 'error', true, drawChart);
                        console.error('Error fetching chart data:', error);
                    });
            }

            const tableBodyRequests = document.querySelector('.request-table tbody');

            function fetchTestRequests() {
                fetch('<?= ROOT ?>/Lab/testRequests')
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = `<tr><td colspan="2" style="text-align: center;">No ongoing tests</td></tr>`;
                        } else {
                            data.forEach(request => {
                                html += `
                                <tr>
                                    <td>${request.patient_id}</td>
                                    <td><span class="status ${request.state}">${request.state}</span></td>
                                </tr>
                            `;
                            });
                        }
                        tableBodyRequests.innerHTML = html;
                    })
                    .catch(error => {
                        showPopup('Error fetching test requests. Please try again', 'error', true, fetchTestRequests);
                        tableBodyRequests.innerHTML = `<tr><td colspan="2">Error loading test requests.</td></tr>`;
                        console.error('Error:', error);
                    });
            }

            fetchTestRequests();
            setInterval(fetchTestRequests, 3000);


            const tableBodyMessages = document.querySelector('.message-table tbody');
            const noMessagesRow = `
                <tr>
                    <td colspan="2" style="text-align: center;">No new messages</td>
                </tr>
            `;

            function formatTimeToAmPm(time) {
                const date = new Date(time);
                return date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

            function fetchNewMessages() {
                fetch('<?= ROOT ?>/Lab/fetchNewMessages')
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length === 0) {
                            html = noMessagesRow;
                        } else {
                            data.forEach(message => {
                                const time = formatTimeToAmPm(message.last_message_date);
                                html += `
                                <tr>
                                    <td>${message.first_name}</td>
                                    <td>${time}</td>
                                </tr>
                            `;
                            });
                        }
                        tableBodyMessages.innerHTML = html;
                    })
                    .catch(error => {
                        showPopup('Error fetching messages. Please try again', 'error', true, fetchNewMessages);
                        tableBodyMessages.innerHTML = `<tr><td colspan="2">Error loading messages.</td></tr>`;
                        console.error('Error fetching messages:', error);
                    });
            }

            fetchNewMessages();
            setInterval(fetchNewMessages, 5000);

            function updateState() {
                fetch('<?= ROOT ?>/ChatController/loggedin')
                    .catch(error => console.error("Error in loggedin:", error));
            }

            setInterval(updateState, 3000);

            function updateReceivedState(receiverId) {
                fetch(`<?= ROOT ?>/ChatController/updateReceivedState/${receiverId}`)
                    .catch(error => console.error("Error updating timestamps:", error));
            }

            setInterval(() => updateReceivedState(<?php echo $_SESSION['USER']->id; ?>), 3000);
        });
    </script>
</body>

</html>