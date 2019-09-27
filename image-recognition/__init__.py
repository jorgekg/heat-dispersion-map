import cv2
import numpy as np
import requests
import time
import json
from PIL import Image
import os
import pymysql

recognizer = cv2.face.LBPHFaceRecognizer_create()

recognizer.read('../bucked/train.yml')

cascadePath = "../bucked/faceIndex.xml"

faceCascade = cv2.CascadeClassifier(cascadePath)

font = cv2.FONT_HERSHEY_SIMPLEX

application = None

# read application properties
with open('../application.json') as json_file:
    application = json.load(json_file)

bd = pymysql.connect(host=application['db']['host'],
                     user=application['db']['user'],
                     password=application['db']['password'],
                     db='faces',
                     cursorclass=pymysql.cursors.DictCursor)

while True:
    try:
        # get next face
        cursor = bd.cursor()
        cursor.execute(
            'SELECT * FROM `faces`.`face` WHERE personId IS NULL LIMIT 1')
        face = cursor.fetchone()
        # verify face exists
        if (face == None):
            print('all images proccessed')
            # await 3 seconds for next iteration
            time.sleep(5)
            continue

        # get image of bucked
        imagePath = "../bucked/faces/dataset." + str(face['id']) + ".jpg"

        # read image
        img = cv2.imread(imagePath, 0)

        # read face on multiscale
        faces = faceCascade.detectMultiScale(img, 1.1, 5)
        if (len(faces) == 0):

            # update face with recognizer face
            try:
                print('Not face detected ' + str(face['id']) + ' to person - 1')
                cursor.execute('UPDATE `faces`.`face` SET `personId`= -1 WHERE  `id`=' + str(face['id']))
                bd.commit()
            except:
                bd.rollback()
                continue

        for(x, y, w, h) in faces:

            print('face detected: ' + str(face['id']))

            # get image face
            predict = img[(y - 40): (y + h) + 40, (x - 20): (x + w) + 20]

            id = 0
            conf = 0

            try:
                # get recognition
                id, conf = recognizer.predict(predict)
            except:
                print('recognition fail')
                cursor.execute('UPDATE `faces`.`face` SET `personId`= -1 WHERE  `id`=' + str(face['id']))
                bd.commit()
                continue

            if (conf > application['face']['recognition']):

                inverterPredict = cv2.flip(predict, 1)
                id, conf = recognizer.predict(inverterPredict)

            print("id: " + str(id) + " - face confidence: " + str(conf))

            # verify confiability of face recognition
            if (conf > application['face']['recognition']):

                print('starting new index')

                person = None

                # generate new traning
                try:
                    # create new person
                    cursor.execute('INSERT INTO `faces`.`person` (`data`) VALUES (0)')
                    bd.commit()
                    cursor.execute('SELECT MAX(id) AS id FROM person')
                    person = cursor.fetchone()
                except:
                    bd.rollback()
                    print('backend failed connection for api person')
                    continue

                if (person == None):
                    continue
                print('indexing person ' + str(person['id']))
                # update training with new image
                recognizer.update([predict], np.array([person['id']]))

                # save and load new training
                recognizer.save('../bucked/train.yml')
                recognizer.read('../bucked/train.yml')

                # save face recognized
                cv2.imwrite("../bucked/people/person." +
                            str(person['id']) + ".jpg", predict)
            else:
                # update face with recognizer face
                try:
                    print('update face ' +
                          str(face['id']) + ' to person ' + str(id))
                    cursor.execute('UPDATE `faces`.`face` SET `personId`= ' + str(id) + ' WHERE  `id`=' + str(face['id']))
                    bd.commit()
                except:
                    bd.rollback()
                    print('backend failed for person id update')
                    continue
        cursor.close()
        if cv2.waitKey(10) & 0xFF == ord('q'):
            break
    except:
        bd.rollback()
        print('backend failed connection for face next')
        time.sleep(5)
        continue
