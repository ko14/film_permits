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
select eachyear, eachcategory, case when count_cat_year is null then 0 else count_cat_year end final_count_cat_year
from
(
select eachyear,eachcategory 
from
(select distinct category eachcategory from permits where eventtype = 'Shooting Permit' and year(startdatetime) >= year(curdate())-5) eachcategory
join 
(select distinct year(startdatetime) eachyear from permits where eventtype = 'Shooting Permit' and year(startdatetime) >= year(curdate())-5) eachyear 
) all_year_categories
left join
(
select year(startdatetime) qyear,category,count(*) count_cat_year
from 
(select distinct eventID,startdatetime,category from permits where eventtype='Shooting Permit' and year(startdatetime) >= year(curdate())-5) eachpermit 
group by year(startdatetime),category
) actual_count on all_year_categories.eachyear=actual_count.qyear and all_year_categories.eachcategory=actual_count.category
order by eachyear,eachcategory
"); 
    $chart_values = "";
    $year_marker = 0;
    $category_list = array();
    while ($resultarr = mysqli_fetch_assoc($result)){ 
        if ($resultarr['eachyear'] != $year_marker){
            if (in_array($resultarr['eachcategory'],$category_list) == false)
            {$category_list[] = $resultarr['eachcategory'];}
            $chart_values .= "],\n";
            $chart_values .= "['$resultarr[eachyear]',$resultarr[final_count_cat_year]";
            $year_marker = $resultarr['eachyear'];    
        }
        else{
            if (in_array($resultarr['eachcategory'],$category_list) == false)
            {$category_list[] = $resultarr['eachcategory'];}            
            $chart_values .= ",$resultarr[final_count_cat_year]"; 
        }
    } 
    $chart_values = substr($chart_values,2,strlen($chart_values)) . "]";
    $category_header = "['Category'";
    foreach ($category_list as $cat){
        $category_header .= ",'$cat'";
    }                   
    $category_header .= "],\n"; 
    
    echo "
      <script>

      google.charts.load('current', {packages:['corechart']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
      var data = google.visualization.arrayToDataTable([
        $category_header
        $chart_values
      ]);

      var options = {
        title: 'Per Year, Category Permits',  
        legend: { position: 'top' , maxLines: 5 },
        isStacked: true,
        hAxis: {  title: 'Count'},
      };
      
      var chart = new google.visualization.BarChart(document.getElementById('graph_div'));
      chart.draw(data, options);

    };
    </script>
    ";
    ?>   
  </head>
  <body>
        <div id="graph_div" style="width:100%;height:380px"></div>

  </body>
</html>
