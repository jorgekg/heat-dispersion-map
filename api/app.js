const express = require('express');
const mariadb = require('mariadb');

const FaceDetect = require('./src/controllers/face-detect');

const fs = require('fs');
const json = fs.readFileSync('../application.json');

let application = JSON.parse(json);

const pool = mariadb.createPool({
    host: application.host,
    user: application.user,
    connectionLimit: application.connectionLimit,
    database: application.database
});

const app = express();

new FaceDetect(app, pool);

app.listen(8090, () => console.log('api its working'));