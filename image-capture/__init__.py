import cv2
import requests
import time
import json
import numpy as np
import pymysql
import time


def adjust_gamma(image, gamma=1):
    # build a lookup table mapping the pixel values [0, 255] to
    # their adjusted gamma values
    invGamma = 1.0 / gamma
    table = np.array([((i / 255.0) ** invGamma) * 255
                      for i in np.arange(0, 256)]).astype("uint8")

    # apply gamma correction using the lookup table
    return cv2.LUT(image, table)


application = None

# app applications
with open('../application.json') as json_file:
    application = json.load(json_file)

# load CascadeClassifier
face_detector = cv2.CascadeClassifier('../bucked/faceIndex.xml')

# get straming of camera
camera = cv2.VideoCapture(0)

first = True

while True:
    try:
        bd = pymysql.connect(host=application['db']['host'],
                             user=application['db']['user'],
                             password=application['db']['password'],
                             db='faces',
                             cursorclass=pymysql.cursors.DictCursor)
        # get imediate image
        status, image = camera.read()

        if (first):
            first = False
            continue

        image = cv2.flip(image, 1, 0)

        # set imagem to gray scale
        imageOfGrayScale = adjust_gamma(image)

        # detect face on multi scale
        faces = face_detector.detectMultiScale(imageOfGrayScale, 1.3, 5)

        # verify face exists
        if (len(faces) != 0):

            # loop on face detected
            for (x, y, w, h) in faces:

                try:
                    cursor = bd.cursor()
                    cursor.execute('SELECT MAX(id) + 1 AS id FROM face')
                    faceData = cursor.fetchone()
                    cursor.execute(
                        'INSERT INTO `faces`.`face` (`sync`) VALUES (0)')
                    print("Face detected saved ", faceData['id'])
                    # write on bucked image
                    cv2.imwrite("../bucked/faces/dataset." + str(
                        faceData['id']) + ".jpg", imageOfGrayScale[(y - 40): (y + h) + 40, (x - 20): (x + w) + 20])
                    bd.commit()
                    time.sleep(0.5)
                except:
                    bd.rollback()
                    print('not write face or not save on disk')
                    time.sleep(10)
        bd.close()
    except:
        print('ocurre a internal erro')
        time.sleep(1)
