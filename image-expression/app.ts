import * as faceapi from '../face-api.js/dist/face-api';
const mariadb = require('mariadb');
const request = require('request');

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

  const img = await canvas.loadImage('../bucked/faces/dataset.' + faceData.id + '.jpg')
  const results = await faceapi.detectAllFaces(img, faceDetectionOptions)
    .withFaceLandmarks()
    .withFaceExpressions() as any;

  if (results && results.length > 0) {
    const [result] = results;
    const [expression] = Object.keys(result.expressions).map(expression => {
      return {
        key: expression,
        confidence: result.expressions[expression]
      };
    }).sort((a, b) => b.confidence - a.confidence);
    if (expression) {
      return expression;
    }
  }
  return {
    key: -1
  };

}

const getImages = async () => {
  console.log('loaging weights');
  await (faceDetectionNet as any).loadFromDisk('../bucked/weights')
  await (faceapi.nets.faceLandmark68Net as any).loadFromDisk('../bucked/weights')
  await (faceapi.nets.faceExpressionNet as any).loadFromDisk('../bucked/weights')

  while (true) {
    console.log('------ starting new iteration -----')
    try {
      const conn = await pool.getConnection();
      const data = await conn.query('SELECT * FROM `faces`.`face` WHERE expression IS NULL LIMIT 1');
      if (data && data.length > 0) {
        console.log('processing new image');
        const expression = await run(data[0]);
        await conn.query('UPDATE `faces`.`face` SET `expression`= "' + expression.key + '" WHERE  `id`=' + data[0].id);
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
