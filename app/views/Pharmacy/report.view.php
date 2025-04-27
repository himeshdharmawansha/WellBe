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
   <div class="pharmacy-report-dashboard-container">
      <?php $this->renderComponent('navbar', $active); ?>
      <div class="pharmacy-report-main-content">
         <?php
         $pageTitle = "Generate Report";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>
         <div class="pharmacy-report-dashboard-content">
            <div class="pharmacy-report-welcome-message">
               <h4 class="pharmacy-report-welcome">Welcome <?= $_SESSION['USER']->first_name ?></h4>
               <h4 class="pharmacy-report-date"><?php echo date('j M, Y'); ?></h4>
            </div>
            <div class="pharmacy-report-generate">
               <div class="pharmacy-report-inputs">
                  <label for="start">Start:</label>
                  <input type="date" name="start" id="start">
                  <label for="end">End:</label>
                  <input type="date" name="end" id="end">
               </div>
               <button class="pharmacy-report-report">Generate Report</button>
            </div>
         </div>
      </div>
      <!-- Error/Success Popup -->
      <div class="popup" id="error-popup">
         <span class="close-btn" onclick="closePopup()">×</span>
         <span id="popup-message"></span>
         <button class="retry-btn" id="retry-btn" style="display:none;">Retry</button>
      </div>
      <!-- Report Popup Overlay -->
      <div class="pharmacy-report-popup-overlay" id="report-popup-overlay">
         <div class="pharmacy-report-popup-content">
            <div class="pharmacy-report-header-container">
               <div class="pharmacy-report-header-title">
                  <h1>Medication Report</h1>
               </div>
               <div class="pharmacy-report-header-right">
                  <img src="<?= ROOT ?>/assets/images/logo.png" alt="WellBe Logo" class="pharmacy-report-header-image">
               </div>
            </div>
            <div class="pharmacy-report-header-left">
               <h4>WELLBE</h4>
               <p>By <strong><?= $_SESSION['USER']->first_name ?></strong></p>
               <p id="date-range"></p>
            </div>
            <div class="pharmacy-report-popup-body">
               <button class="pharmacy-report-popup-close">×</button>
               <div id="pie_chart" style="width: 100%; height: 300px;margin-left: 100px;"></div>
               <div id="line_chart" style="width: 100%; height: 300px;"></div>
            </div>
            <button class="pharmacy-report-popup-print" onclick="window.print()">Print</button>
         </div>
      </div>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const reportButton = document.querySelector('.pharmacy-report-report');
         const startDateInput = document.getElementById('start');
         const endDateInput = document.getElementById('end');
         const popupOverlay = document.getElementById('report-popup-overlay');
         const popupClose = popupOverlay.querySelector('.pharmacy-report-popup-close');

         // Popup logic (error/success)
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

         // Date validation function
         function isDateNotInFuture(dateStr) {
            const currentDate = new Date('2025-04-26'); // Current date as per system
            const inputDate = new Date(dateStr);
            if (isNaN(inputDate.getTime())) {
               return false; // Invalid date format
            }
            return inputDate <= currentDate;
         }

         const today = new Date('2025-04-26'); // Current date as per system
         const thirtyDaysAgo = new Date(today);
         thirtyDaysAgo.setDate(today.getDate() - 30);

         const formatDateInputValue = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
         };

         startDateInput.value = formatDateInputValue(thirtyDaysAgo);
         endDateInput.value = formatDateInputValue(today);

         popupClose.addEventListener('click', () => {
            popupOverlay.style.display = 'none';
         });

         reportButton.addEventListener('click', () => {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            // Validation: Check for empty dates
            if (!startDate || !endDate) {
               showPopup('Please select both start and end dates.');
               return;
            }

            // Validation: Check if start date is before end date
            if (new Date(startDate) > new Date(endDate)) {
               showPopup('Start date must be before end date.');
               return;
            }

            // Validation: Check if dates are not in the future
            if (!isDateNotInFuture(startDate)) {
               showPopup('Start date cannot be in the future.');
               return;
            }
            if (!isDateNotInFuture(endDate)) {
               showPopup('End date cannot be in the future.');
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
               fetch(`<?= ROOT ?>/Pharmacy/generateReport?start_date=${startDate}&end_date=${endDate}`)
                  .then((response) => response.json())
                  .then((data) => {
                     if (data.error) {
                        showPopup(data.error);
                        popupOverlay.style.display = 'none';
                        return;
                     }
                     drawPieChart(data.medications);
                     drawLineChart(data.requests);
                     showPopup('Report generated successfully', 'success');
                  })
                  .catch((error) => {
                     showPopup('Error generating report. Please try again', 'error', true, () => drawCharts(startDate, endDate));
                     popupOverlay.style.display = 'none';
                     console.error('Error fetching report data:', error);
                  });
            });
         }

         function drawPieChart(medications) {
            const chartData = [
               ['Medication', 'Usage']
            ];
            medications.forEach((med) => {
               chartData.push([med.medication_name, parseInt(med.count)]);
            });

            const data = google.visualization.arrayToDataTable(chartData);

            var options = {
               title: 'Medication Usage Distribution',
               pieSliceText: 'percentage',
               legend: { position: 'right' },
               chartArea: { width: '100%', height: '80%' }
            };

            const chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
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