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
        # get next face
        face_request = requests.get(url=api + '/face')
        face = face_request.json()
        # verify face exists
        if (face == {}):
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
    if (len(faces) == 0):

        # update face with recognizer face
        try:
            print('update face ' + str(face['id']) + ' to person ' + str(0))
            person_reques = requests.put(
                url=api + '/face/' + str(face['id']), json={"personId": str(0)})
            print('image updated')
        except:
            print('backend failed connection for person id update')
            time.sleep(5)
            continue

    for(x, y, w, h) in faces:

        print('face detected: ' + str(face['id']))

        # get image face
        predict = img[(y - 80): (y + h) + 80, (x - 20): (x + w) + 20]

        id = 0
        conf = 0

        try:
            # get recognition
            id, conf = recognizer.predict(predict)
        except:
            print('recognition fail')
            person_reques = requests.put(
                    url=api + '/face/' + str(face['id']), json={"personId": str(0)})

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
                person_request = requests.post(
                    url=api + '/person/' + str(face['id']))
                person = person_request.json()

                # verify exists person
                if (person == None):
                    # await 3 seconds for next iterations
                    time.sleep(3)
                    continue
            except:
                print('backend failed connection for api person')
                time.sleep(5)
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
                person_reques = requests.put(
                    url=api + '/face/' + str(face['id']), json={"personId": str(id)})
                print('image updated')
            except:
                print('backend failed connection for person id update')
                time.sleep(5)
                continue

    if cv2.waitKey(10) & 0xFF == ord('q'):
        break
