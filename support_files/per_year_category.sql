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
