import json
import urllib.request
import sqlalchemy

def dailyload():
    db = sqlalchemy.create_engine(
        sqlalchemy.engine.url.URL( 
            drivername="mysql+pymysql",
            username=username,
            password=password,
            database=database,
        ),
    )
    
    with urllib.request.urlopen('https://data.cityofnewyork.us/resource/tg4x-b46p.json') as f:
        permit_data = f.read().decode('utf-8')
    permit_data_json = json.loads(permit_data)

    for row in permit_data_json:
        zips = row["zipcode_s"].split(",")
        for each_zip in zips:
            query = "replace into permits values ("
            for key, value in row.items():
                query += '"' + value + '",'
            query += '"' + each_zip.strip() + '");'
            with db.connect() as conn:
                conn.execute(query)
    return "done"