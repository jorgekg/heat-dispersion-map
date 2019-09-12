const express = require('express');
const app = express();

const PORT = 8080;
const HOST = '0.0.0.0';

let id = 0;

app.post('/api/face_detection', (req, res) => {
    id++;
    console.log(id);
    res.send({id: id})
});

app.listen(PORT, HOST, () => console.log('api its working'));