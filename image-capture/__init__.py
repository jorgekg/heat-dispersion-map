import cv2
import requests
import time

URL = "http://localhost:8080/api/face_detection"

face_detector = cv2.CascadeClassifier('faceIndex.xml')

# get straming of camera
camera = cv2.VideoCapture(0)

while True:
    # get imediate image
    status, image = camera.read()
    
    image = cv2.flip(image,1,0)

    # set imagem to gray scale
    imageOfGrayScale = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # detect face on multi scale
    faces = face_detector.detectMultiScale(imageOfGrayScale, 1.3, 5)

    # verify face exists
    if (len(faces) != 0):

        # loop on face detected
        for (x,y,w,h) in faces:

            # crop face of image
            cv2.rectangle(imageOfGrayScale, (x, y), (x + w, y + h), (255, 0 ,0), 2)

            try:
                face_request = requests.post(url = URL, data = {})
                faceData = face_request.json()
                if (faceData['id'] == None):
                    continue

                print("index new photo", faceData['id'])
                # write on bucked image
                cv2.imwrite("../bucked/news/dataset." + str(faceData['id']) +".jpg", imageOfGrayScale[y : y + h, x : x + w])
            except:
                print('backend not connected')
                time.sleep(10)

            