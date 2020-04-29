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
