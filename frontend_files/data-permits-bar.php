<!DOCTYPE html>                        
<html lang="en-US">
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <?php
    
    #fill in your parameters    
    $server = "";
    $user = "";
    $password = "";
    $database = "";
    
    $mysqli = new mysqli($server, $user, $password, $database);
    $result = $mysqli->query("select p.Zip zip, count(*) zip_count
                            from permits p
                            join coords on p.Zip = coords.zip
                            join (select max(startdatetime) max_start from permits) temp 
                            where eventtype = 'Shooting Permit' and p.Zip not in ('0','N/A')
                            and startdatetime>=DATE_SUB(max_start, INTERVAL 12 MONTH)
                            group by p.Zip order by count(*) desc limit 10"); 
    $bar_chart_values = "";
    while ($resultarr = mysqli_fetch_assoc($result)){
       $bar_chart_values .= "['$resultarr[zip]',$resultarr[zip_count],'#8A0808'],\n";
    }                               
    echo "
     <script>     
    
      google.charts.load('current', {packages: ['corechart']});        
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
       var data = google.visualization.arrayToDataTable(
        [
         ['Zip', 'Permits', { role: 'style' }],
         $bar_chart_values 
        ]
       );

       var view = new google.visualization.DataView(data);
       view.setColumns([0, 1,
                       { calc: 'stringify',
                         sourceColumn: 1,
                         type: 'string',
                         role: 'annotation' },
                       2]);

      var options = {
        title: 'Last 12 mo of Permits - Top 10 Zip Codes',
        width: 530,
        height: 380,
        bar: {groupWidth: '80%'},
        legend: { position: 'none' },
        hAxis: {  title: 'Count'},        
      };

        var visualization = new google.visualization.BarChart(document.getElementById('total_permits_div'));

        visualization.draw(view, options);
      }

     </script> 
    ";
    ?>   
  </head>
  <body>
        <div id="total_permits_div" style="padding:4px;width:100%;height:350px"></div>

  </body>
</html>
