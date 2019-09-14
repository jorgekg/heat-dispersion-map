import cv2
import numpy as np
import requests
import time
import json
from PIL import Image
import os

recognizer = cv2.face.LBPHFaceRecognizer_create()

recognizer.read('../bucked/train.yml')

cascadePath = "../bucked/faceIndex.xml"

faceCascade = cv2.CascadeClassifier(cascadePath)

font = cv2.FONT_HERSHEY_SIMPLEX

application = None

# read application properties
with open('../application.json') as json_file:
    application = json.load(json_file)

# denined api end point
api = application["api"] + ':' + application['port'] + '/api'

while True:
    try:
        try:
            # get next face
            face_request = requests.get(url=api + '/face_next')
            face = face_request.json()

            # verify face exists
            if (face == None):
                print('all images proccessed')
                # await 3 seconds for next iteration
                time.sleep(3)
                continue
        except:
            print('backend failed connection for face next')
            time.sleep(5)
            continue

        # get image of bucked
        imagePath = "../bucked/faces/dataset." + str(face['id']) + ".jpg"

        # read image
        img = cv2.imread(imagePath, 0)

        # read face on multiscale
        faces = faceCascade.detectMultiScale(img, 1.1, 5)

        for(x, y, w, h) in faces:

            print('face detected')

            # get image face
            predict = img[y:y+h, x:x+w]

            # get recognition
            id, conf = recognizer.predict(predict)
            
            print(id)
            print(conf)

            # verify confiability of face recognition
            if (conf > application['face']['recognition']):

                # generate new traning
                try:

                    # create new person
                    person_reques = requests.post(
                        url=api + '/people/' + str(face['id']))
                    person = person_reques.json()

                    # verify exists person
                    if (not len(person)):
                        # await 3 seconds for next iterations
                        time.sleep(3)
                        continue
                except:
                    print('backend failed connection for api person')
                    time.sleep(5)
                    continue

                # update training with new image
                recognizer.update([predict], np.array([person['id']]))

                # save and load new training
                recognizer.save('../bucked/train.yml')
                recognizer.read('../bucked/train.yml')

                # save face recognized
                cv2.imwrite("../bucked/people/person." + str(person['id']) + ".jpg", predict)
            else:
                # update face with recognizer face
                try:
                    person_reques = requests.post(url=api + '/face/' + str(id))
                except:
                    print('backend failed connection for person id update')
                    time.sleep(5)
                    continue

        if cv2.waitKey(10) & 0xFF == ord('q'):
            break
    except:
        print('ocurred internal error')
        time.sleep(5)
