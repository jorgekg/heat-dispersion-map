import cv2
import requests
import time
import json

application = None

# app applications
with open('../application.json') as json_file:
    application = json.load(json_file)

# host api
api = application["api"] + ':' + application['port'] + '/api'

# load CascadeClassifier
face_detector = cv2.CascadeClassifier('../bucked/faceIndex.xml')

# get straming of camera
camera = cv2.VideoCapture(0)

while True:
    try:
        # get imediate image
        status, image = camera.read()

        image = cv2.flip(image, 1, 0)

        # set imagem to gray scale
        imageOfGrayScale = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

        # detect face on multi scale
        faces = face_detector.detectMultiScale(imageOfGrayScale, 1.3, 5)

        # verify face exists
        if (len(faces) != 0):

            # loop on face detected
            for (x, y, w, h) in faces:

                # crop face of image
                cv2.rectangle(imageOfGrayScale, (x, y),
                              (x + w, y + h), (255, 0, 0), 2)

                try:
                    face_request = requests.post(url=api + '/face', data={})
                    faceData = face_request.json()
                    if (faceData == None):
                        continue

                    print("index new photo", faceData['id'])
                    # write on bucked image
                    cv2.imwrite("../bucked/faces/dataset." + str(
                        faceData['id']) + ".jpg", imageOfGrayScale[y: y + h, x: x + w])
                except:
                    print('backend not connected')
                    time.sleep(10)
    except:
        print('ocurred internal error')
        time.sleep(10)
