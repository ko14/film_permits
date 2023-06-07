# film_permits

The backend is python and mysql. The frontend uses Google Charts API (https://developers.google.com/chart).

The daily load procedure receives the latest records from the API, which is about 1k. The load procedures reformats the data so that each record in the database table is a unique combo of EventID (aka permit) & zip code (since one permit can have many locations and zip codes).

This data on NYC Open Data is updated daily but has a lag, so the latest permit is usually a few months old. https://data.cityofnewyork.us/City-Government/Film-Permits/tg4x-b46p

