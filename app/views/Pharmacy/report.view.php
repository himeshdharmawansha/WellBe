<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Pharmacy/report.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
   <div class="dashboard-container">
      <?php $this->renderComponent('navbar', $active); ?>
      <div class="main-content">
         <?php
         $pageTitle = "Generate Report";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>
         <div class="dashboard-content">
            <div class="welcome-message">
               <h4 class="welcome">Welcome <?= $_SESSION['USER']->first_name ?></h4>
               <h4 class="date"><?php echo date('j M, Y'); ?></h4>
            </div>
            <div class="generate">
               <button class="report">Generate Report</button>
            </div>
         </div>
      </div>
   </div>
   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const reportButton = document.querySelector('.report');
         const popupOverlay = document.createElement('div');
         popupOverlay.classList.add('popup-overlay');

         popupOverlay.innerHTML = `
            <div class="popup-content">
               <div class="header-container">
                  <div class="header-title">
                     <h1>Medication Report</h1>
                  </div>
                  <div class="header-right">
                     <img src="<?= ROOT ?>/assets/images/logo.png" alt="WellBe Logo" class="header-image">
                  </div>
               </div>
               <div class="header-left">
                  <h4>WELLBE</h4>
                  <p>By <strong><?= $_SESSION['USER']->first_name ?></strong></p>
                  <p id="date-range"></p>
               </div>
               <div class="popup-body">
                  <button class="popup-close">&times;</button>
                  <div id="bar_chart" style="width: 100%; height: 300px;"></div>
                  <div id="line_chart" style="width: 100%; height: 300px;"></div>
               </div>
               <!-- Print Button -->
               <button class="popup-print" onclick="window.print()">Print</button>
            </div>
              `;

         document.body.appendChild(popupOverlay);

         const popupClose = popupOverlay.querySelector('.popup-close');
         popupClose.addEventListener('click', () => {
            popupOverlay.style.display = 'none';
         });

         reportButton.addEventListener('click', () => {
            popupOverlay.style.display = 'flex';
            drawCharts();
         });

         function drawCharts() {
            // Load Google Charts library
            google.charts.load('current', {
               packages: ['corechart']
            });

            // Callback after Google Charts is loaded
            google.charts.setOnLoadCallback(() => {
               fetch('<?= ROOT ?>/PharmacyReport/generateReport')
                  .then(response => response.json())
                  .then(data => {
                     drawBarChart(data.medications);
                     drawLineChart(data.requests);
                  })
                  .catch(error => console.error('Error fetching report data:', error));
            });
         }

         function drawBarChart(medications) {
            const chartData = [
               ['Medication', 'Usage']
            ];
            medications.forEach(med => {
               chartData.push([med.medication_name, parseInt(med.count)]);
            });

            const data = google.visualization.arrayToDataTable(chartData);

            const options = {
               title: 'Medication Usage',
               hAxis: {
                  title: 'Type of Medications',
               },
               vAxis: {
                  title: 'Usage',
               },
               colors: ['#1a73e8'],
            };

            const chart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
            chart.draw(data, options);
         }

         function drawLineChart(requests) {
            const chartData = [
               ['Day', 'Requests'] // Header row
            ];

            // Map each date to a day number (1 to 30)
            const today = new Date();
            const dateToDayNumber = {};

            // Generate date-to-day mappings
            for (let i = 0; i < 30; i++) {
               const date = new Date();
               date.setDate(today.getDate() - i); // Subtract days to get past dates
               const formattedDate = date.toISOString().split('T')[0]; // Format as YYYY-MM-DD
               dateToDayNumber[formattedDate] = 30 - i; // Map to day number
            }

            // Populate chart data
            requests.forEach(req => {
               const dayNumber = dateToDayNumber[req.request_date];
               if (dayNumber) {
                  chartData.push([
                     dayNumber.toString(), // Day as a string ('1', '2', ..., '30')
                     parseInt(req.request_count) // Request count
                  ]);
               }
            });

            // Prepare data for the chart
            const data = google.visualization.arrayToDataTable(chartData);

            // Chart options
            const options = {
               title: 'Medication Requests',
               curveType: 'function',
               hAxis: {
                  title: 'Past 30 days',
               },
               vAxis: {
                  title: 'Requests',
               },
               colors: ['#34a853'],
            };

            // Draw the chart
            const chart = new google.visualization.LineChart(document.getElementById('line_chart'));
            chart.draw(data, options);
         }

         function updateDateRangeDisplay() {
            const today = new Date();
            const pastDate = new Date();
            pastDate.setDate(today.getDate() - 29); // Subtract 29 to include today as the 30th day

            // Format dates as "1 August, 2024"
            const formatDate = (date) => {
               return date.toLocaleDateString('en-GB', {
                  day: 'numeric',
                  month: 'short',
                  year: 'numeric'
               });
            };

            // Get formatted date range
            const startDate = formatDate(pastDate);
            const endDate = formatDate(today);

            // Update the HTML dynamically
            const dateRangeElement = document.getElementById('date-range');
            dateRangeElement.textContent = `${startDate} to ${endDate}`;
         }

         // Call the function to update the date range when the page loads
         updateDateRangeDisplay();

      });
   </script>
</body>

</html>