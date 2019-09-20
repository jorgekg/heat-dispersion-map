import * as faceapi from '../face-api.js/dist/face-api';
const request = require('request');

const fs = require('fs');

let json = fs.readFileSync('../application.json');
let application = JSON.parse(json);

import { canvas, faceDetectionNet, faceDetectionOptions, saveFile } from './commons';

async function run(faceData) {

  await faceDetectionNet.loadFromDisk('../bucked/weights')
  await faceapi.nets.faceLandmark68Net.loadFromDisk('../bucked/weights')
  await faceapi.nets.ageGenderNet.loadFromDisk('../bucked/weights')

  const img = await canvas.loadImage('../bucked/faces/dataset.' + faceData.id + '.jpg')
  const [results] = await faceapi.detectAllFaces(img, faceDetectionOptions)
    .withFaceLandmarks()
    .withAgeAndGender() as any;
  if (results) {
    request({
      url: `${application.api}:${application.port}/api/face`,
      method: 'PUT',
      json: { id: faceData.id, gender: results.gender, age: results.age }
    })
  } else {
    request.put(`${application.api}:${application.port}/api/face/`+faceData.id,
      {json: { id: faceData.id, gender: -1, age: -1 }},
      (error, res, body) => {
        if (error) {
          console.error(error)
          return
        }
        console.log(`statusCode: ${res.statusCode}`)
        console.log(body)
      }
    )
  }
}

setInterval(() => {
  console.log('nova iteração')
  request.get(`${application.api}:${application.port}/api/face/gender_age`, { json: true }, (err, res, body) => {
    if (body) {
      console.log(body);
      run(body).then().catch(err => console.log(err));
    }
  });
}, 1000);
