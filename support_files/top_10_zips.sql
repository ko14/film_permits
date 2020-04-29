select zip, count(*) zip_count
from permits
join (select max(startdatetime) max_start from permits) as temp  
where eventtype = 'Shooting Permit' and zip not in ('0','N/A')
and startdatetime>=DATE_SUB(max_start, INTERVAL 12 MONTH)
group by zip order by count(*) desc limit 10
