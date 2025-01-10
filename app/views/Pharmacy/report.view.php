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
               <div class="inputs">
                  <label for="start">Start:</label>
                  <input type="date" name="start" id="start">
                  <label for="end">End:</label>
                  <input type="date" name="end" id="end">
               </div>
               <button class="report">Generate Report</button>
            </div>
         </div>

      </div>
   </div>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const reportButton = document.querySelector('.report');
         const startDateInput = document.getElementById('start');
         const endDateInput = document.getElementById('end');
         const popupOverlay = document.createElement('div');
         popupOverlay.classList.add('popup-overlay');

         // Set default dates
         const today = new Date();
         const thirtyDaysAgo = new Date();
         thirtyDaysAgo.setDate(today.getDate() - 30);

         const formatDateInputValue = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
         };

         startDateInput.value = formatDateInputValue(thirtyDaysAgo);
         endDateInput.value = formatDateInputValue(today);

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
               <button class="popup-print" onclick="window.print()">Print</button>
            </div>
         `;

         document.body.appendChild(popupOverlay);

         const popupClose = popupOverlay.querySelector('.popup-close');
         popupClose.addEventListener('click', () => {
            popupOverlay.style.display = 'none';
         });

         reportButton.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (!startDate || !endDate) {
               alert('Please select both start and end dates.');
               return;
            }

            const formatDateDisplay = (date) => {
               return new Date(date).toLocaleDateString('en-GB', {
                  day: 'numeric',
                  month: 'short',
                  year: 'numeric',
               });
            };

            document.getElementById('date-range').textContent = `${formatDateDisplay(startDate)} to ${formatDateDisplay(endDate)}`;

            popupOverlay.style.display = 'flex';
            drawCharts(startDate, endDate);
         });

         function drawCharts(startDate, endDate) {
            google.charts.load('current', {
               packages: ['corechart']
            });

            google.charts.setOnLoadCallback(() => {
               fetch(`<?= ROOT ?>/PharmacyReport/generateReport?start_date=${startDate}&end_date=${endDate}`)
                  .then((response) => response.json())
                  .then((data) => {
                     drawBarChart(data.medications);
                     drawLineChart(data.requests);
                  })
                  .catch((error) => console.error('Error fetching report data:', error));
            });
         }

         function drawBarChart(medications) {
            const chartData = [
               ['Medication', 'Usage']
            ];
            medications.forEach((med) => {
               chartData.push([med.medication_name, parseInt(med.count)]);
            });

            const data = google.visualization.arrayToDataTable(chartData);

            const options = {
               title: 'Medication Usage',
               hAxis: {
                  title: 'Type of Medications'
               },
               vAxis: {
                  title: 'Usage'
               },
               colors: ['#1a73e8'],
            };

            const chart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
            chart.draw(data, options);
         }

         function drawLineChart(requests) {
            const chartData = [
               ['Date', 'Requests']
            ];
            requests.forEach((req) => {
               chartData.push([new Date(req.request_date), parseInt(req.request_count)]);
            });

            const data = google.visualization.arrayToDataTable(chartData);

            const options = {
               title: 'Daily Medication Requests',
               curveType: 'function',
               hAxis: {
                  title: 'Date',
                  format: 'MMM dd, yyyy'
               },
               vAxis: {
                  title: 'Number of Requests'
               },
               colors: ['#34a853'],
            };

            const chart = new google.visualization.LineChart(document.getElementById('line_chart'));
            chart.draw(data, options);
         }
      });
   </script>
</body>

</html>