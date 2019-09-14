const express = require('express');
const fs = require('fs');

const FaceController = require('./controllers/face.controller');
const PersonController = require('./controllers/person.controller');

let json = fs.readFileSync('../application.json');
let application = JSON.parse(json);

const app = express();

app.use(express.urlencoded({ extended: false }));

new FaceController(app);
new PersonController(app);

app.listen(application.port, application.host, () => console.log(`app its start on ${application.port}`));