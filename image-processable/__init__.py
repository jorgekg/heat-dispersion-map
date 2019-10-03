import pymysql
import json
import os
import time
import cv2

application = None

# read application properties
with open('application.json') as json_file:
    application = json.load(json_file)

while True:
    try:
        bd = pymysql.connect(host=application['db']['host'],
                             user=application['db']['user'],
                             password=application['db']['password'],
                             db='faces',
                             cursorclass=pymysql.cursors.DictCursor)
        cursor = bd.cursor()
        cursor.execute(
            'SELECT * FROM `faces`.`face` WHERE personId IS NOT NULL AND gender IS NOT NULL AND age IS NOT NULL AND expression IS NOT NULL and sync = 0 LIMIT 1')
        face = cursor.fetchone()
        if (face == None):
            print('all images proccessed')
            # await 3 seconds for next iteration
            time.sleep(5)
        else:
            print(face)
            if (face['personId'] == -1):
                cursor.execute(
                    'delete from face where id = ' + str(face['id']))
            else:
                query = 'update face set sync = 1 where id = ' + \
                    str(face['id'])
                cursor.execute(query)
            try:
                imagePath = "bucked/faces/dataset." + \
                    str(face['id']) + ".jpg"
                os.remove(imagePath)
            except:
                print('not remove image')
            bd.commit()
            cursor.close()
            bd.close()
    except:
        print('ocorred error')
        time.sleep(1)
