import cv2
import requests
import time
import json
import numpy as np

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

# host api
api = application["api"] + ':' + application['port'] + '/api'

# load CascadeClassifier
face_detector = cv2.CascadeClassifier('../bucked/faceIndex.xml')

# get straming of camera
camera = cv2.VideoCapture(0)

first = True

while True:
    try:
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
                    face_request = requests.post(url=api + '/face', data={})
                    faceData = face_request.json()
                    if (faceData == None):
                        continue

                    print("index new photo", faceData['id'])
                    # write on bucked image
                    cv2.imwrite("../bucked/faces/dataset." + str(
                        faceData['id']) + ".jpg", imageOfGrayScale[(y - 80): (y + h) + 80, (x - 20): (x + w) + 20])
                except:
                    print('backend not connected')
                    time.sleep(10)
    except:
        print('ocurred internal error')
        time.sleep(10)
