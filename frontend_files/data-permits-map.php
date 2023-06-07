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
    $google_maps_api_key = "";  //register with Google to get maps api key
    
    $mysqli = new mysqli($server, $user, $password, $database);
    $result = $mysqli->query("select p.Zip zip, coords.lat, coords.long, count(*) zip_count
                            from permits p
                            join (select max(startdatetime) max_start from permits) temp 
                            join coords on p.Zip = coords.zip
                            where eventtype = 'Shooting Permit' and p.Zip not in ('0','N/A')
                            and startdatetime>=DATE_SUB(max_start, INTERVAL 12 MONTH)
                            group by p.Zip order by count(*) desc limit 10"); 
    $chart_values = "";
    while ($resultarr = mysqli_fetch_assoc($result)){
       $chart_values .= "[$resultarr[lat],$resultarr[long],'$resultarr[zip] ($resultarr[zip_count])'],\n";
    }                               
    echo "
     <script>     
      google.charts.load('current', {'packages': ['map'], 'mapsApiKey': '$google_maps_api_key'});

      google.charts.setOnLoadCallback(drawMap);
       
      function drawMap() {
        var data = google.visualization.arrayToDataTable([
            ['Lat', 'Long', 'Name'],
            $chart_values                                                          
            ]);


        var options = {
         mapType: 'normal',
         showTooltip: true,
         showInfoWindow: true
        };

        var map = new google.visualization.Map(document.getElementById('map_div'));

        map.draw(data, options);
      }


     </script> 
    ";
    ?>    
  </head>
  <body>
        <div id="map_div" style="padding:4px;width:100%;height:350px"></div>

  </body>
</html>
