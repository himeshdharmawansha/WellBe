<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Appointments'],
          ['1/30',  13],
          ['1/31',  11],
          ['2/1',  9],
          ['2/2',  15],
          ['2/3',  11],
          ['2/4',  12],
          ['2/5',  10]
        ]);

        var options = {
          title: 'Appointments of Past Week',
          curveType: 'function',
          legend: { position: 'bottom' },
          animation: {
            startup: true,           // Animates on startup (page load)
            duration: 700,          // Duration of animation in milliseconds
            easing: 'inAndOut'       // Easing style of animation
          },
          chartArea: { 
            left: 40,   // Adjusts the left padding
            top: 30,    // Adjusts the top padding
            right: 0,  // Adjusts the right padding
            bottom: 50, // Adjusts the bottom padding
            width: '80%',   // Adjusts the chart area width
            height: '70%'   // Adjusts the chart area height
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 400px; height: 500px"></div>
  </body>
</html>