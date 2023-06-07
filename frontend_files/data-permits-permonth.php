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
    $result = $mysqli->query("
select film_avg_tbl.startmonth,truncate(film_avg,0) film_avg,truncate(tv_avg,0) tv_avg
from
(
select startmonth,avg(permonth) film_avg
from
(
select year(startdatetime) startyear,month(startdatetime) startmonth,count(*) permonth
from 
(select distinct eventID, startdatetime from permits where eventtype = 'Shooting Permit' and category='film') eachpermit
group by startyear,startmonth
) film
group by startmonth
) film_avg_tbl
join
(
select startmonth,avg(permonth) tv_avg
from
(
select year(startdatetime) startyear,month(startdatetime) startmonth,count(*) permonth
from 
(select distinct eventID, startdatetime from permits where eventtype = 'Shooting Permit' and category='television') eachpermit
group by startyear,startmonth
) tv
group by startmonth
) tv_avg_tbl
on film_avg_tbl.startmonth=tv_avg_tbl.startmonth
order by film_avg_tbl.startmonth
"); 
    $chart_values = "";
    $month_array = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    $month_index = 0;
    while ($resultarr = mysqli_fetch_assoc($result)){
       $chart_values .= "['$month_array[$month_index]',$resultarr[film_avg],$resultarr[tv_avg]],";
       $month_index += 1; 
    }       
    echo "
    <script>

      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Month');
      data.addColumn('number', 'Film');
      data.addColumn('number', 'TV');

      data.addRows([
        $chart_values
      ]);

      var options = {
        chart: {
          title: 'Average Per Month - Film vs TV'
        },
      };

      var chart = new google.charts.Line(document.getElementById('graph_div'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
    </script>
    ";
    ?>   
  </head>
  <body>
        <div id="graph_div" style="width:100%;height:380px"></div>

  </body>
</html>
