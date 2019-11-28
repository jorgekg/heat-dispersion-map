import threading
import subprocess

def faceDetection():
  subprocess.call('python image-capture/__init__.py')

def faceRecognition():
  subprocess.call('python image-recognition/__init__.py')

def faceGenderAndAge():
  subprocess.call('cd image-gender-age-detect && ts-node app.ts', shell=True)

def faceExpression():
  subprocess.call('cd image-expression && ts-node app.ts', shell=True)

def imageProcessable():
  subprocess.call('python image-processable/__init__.py')

# faceDetectionThread = threading.Thread(target=faceDetection)
faceRecognitionThread = threading.Thread(target=faceRecognition) 
faceGenderAndAgeThread = threading.Thread(target=faceGenderAndAge)
faceExpressionThread = threading.Thread(target=faceExpression)
imageProcessableThread = threading.Thread(target=imageProcessable)

# faceDetectionThread.start()
faceRecognitionThread.start()
faceGenderAndAgeThread.start()
faceExpressionThread.start()
imageProcessableThread.start()