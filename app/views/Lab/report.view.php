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
      <!-- Sidebar -->
      <?php
      $this->renderComponent('navbar', $active);
      ?>
      <!-- Main Content -->
      <div class="main-content">
         <!-- Top Header -->
         <?php
         $pageTitle = "Generate Report"; // Set the text you want to display
         include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Lab/header.php';
         ?>
         <!-- Dashboard Content -->
         <div class="dashboard-content">
            <div class="welcome-message">
               <h4 class="welcome">Welcome Mr. K.S.Perera</h4>
               <h4 class="date">10 August, 2024</h4>
            </div>

            <div class="generate">
               <button class="report">Generate Report</button>
            </div>
            <!-- <div class="content-container">
               <div class="dashboard messages">
               </div>
               <div class="dashboard messages">
               </div>
               <div class="dashboard calendar-container">
                  <div id="curve_chart" style="width: 400px; height: 400px; padding:0%;margin:0%"></div>
               </div>

            </div> -->
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