import * as faceapi from '../face-api.js/dist/face-api';
const mariadb = require('mariadb');
const fs = require('fs');

let json = fs.readFileSync('../application.json');
let application = JSON.parse(json);

const pool = mariadb.createPool({
  host: application.db.host,
  user: application.db.user,
  password: application.db.password,
  database: 'faces',
  connectionLimit: 10
});

import { canvas, faceDetectionNet, faceDetectionOptions } from './commons';

const run = async (faceData) => {
  console.log('loading image');
  let img;
  try {
    img = await canvas.loadImage('../bucked/faces/dataset.' + faceData.id + '.jpg');
  } catch (err) {
    return { gender: -1, age: -1 }
  }
  console.log('starting landmarks');
  const results = await faceapi.detectAllFaces(img, faceDetectionOptions)
    .withFaceLandmarks()
    .withAgeAndGender() as any;
  if (results && results.length > 0) {
    console.log('image landmarks landmarks detected');
    const [result] = results;
    return result;
  }
  console.log('image not landmarks');
  return { gender: -1, age: -1 };

}

const getImages = async ()  => {
  console.log('loaging weights');
  await (faceDetectionNet as any).loadFromDisk('../bucked/weights')
  await (faceapi.nets.faceLandmark68Net as any).loadFromDisk('../bucked/weights')
  await (faceapi.nets.ageGenderNet as any).loadFromDisk('../bucked/weights')

  while (true) {
    console.log('------ starting new iteration -----')
    try {
      const conn = await pool.getConnection();
      const data = await conn.query('SELECT * FROM `faces`.`face` WHERE gender IS NULL LIMIT 1');
      if (data && data.length > 0) {
        console.log('processing new image');
        const genderAndAge = await run(data[0]);
        await conn.query(
          'UPDATE `faces`.`face` SET `gender`= "' + genderAndAge.gender + '", age = "' + genderAndAge.age + '" WHERE  `id`=' + data[0].id
        );
      } else {
        console.log('all images processed');
        await sleep(5000);
      }
      conn.end();
    } catch (err) {
      console.log(err);
    }
  }
}

const sleep = async (time) => {
  return new Promise(resolve => setTimeout(resolve, time));
}

getImages().then().catch(err => console.log(err));