import * as faceapi from '../face-api.js/dist/face-api';

import { canvas, faceDetectionNet, faceDetectionOptions, saveFile } from './commons';

async function run() {

  await faceDetectionNet.loadFromDisk('../bucked/weights')
  await faceapi.nets.faceLandmark68Net.loadFromDisk('../bucked/weights')
  await faceapi.nets.ageGenderNet.loadFromDisk('../bucked/weights')

  const img = await canvas.loadImage('../bucked/faces/dataset.33.jpg')
  const results = await faceapi.detectAllFaces(img, faceDetectionOptions)
    .withFaceLandmarks()
    .withAgeAndGender()

  // const out = faceapi.createCanvasFromMedia(img) as any
  // faceapi.draw.drawDetections(out, results.map(res => res.detection))
  console.log(results);
  // console.log('done, saved results to out/ageAndGenderRecognition.jpg')
}

run().then().catch(err => console.log(err));