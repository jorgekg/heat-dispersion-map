const express = require('express');
const mariadb = require('mariadb');
const fs = require('fs');
const bodyParser = require('body-parser');

let json = fs.readFileSync('../application.json');
let application = JSON.parse(json);

const app = express();

const pool = mariadb.createPool({
  host: application.db.host,
  user: application.db.user,
  password: application.db.password,
  database: 'faces',
  connectionLimit: 10
});

app.use(bodyParser.urlencoded({ extended: false }))
app.use(bodyParser.json())

app.use(express.urlencoded({ extended: false }));

app.get('/api/face', async (req, res) => {
  let item = {};
  try {
    const conn = await pool.getConnection();
    const data = await conn.query('SELECT * FROM `faces`.`face` WHERE personId IS NULL LIMIT 1');
    conn.end();
    if (data.length > 0) {
      item = data[0];
    }
  } catch (err) {
    console.log(err)
  }
  res.send(item);
});

app.get('/api/face/gender_age', async (req, res) => {
  let item = {};
  try {
    const conn = await pool.getConnection();
    const data = await conn.query('SELECT * FROM `faces`.`face` WHERE gender IS NULL LIMIT 1');
    conn.end();
    if (data.length > 0) {
      item = data[0];
    }
  } catch (err) {
    console.log(err)
  }
  res.send(item);
});

app.get('/api/face/expression', async (req, res) => {
  let item = {};
  try {
    const conn = await pool.getConnection();
    const data = await conn.query('SELECT * FROM `faces`.`face` WHERE expression IS NULL LIMIT 1');
    conn.end();
    if (data.length > 0) {
      item = data[0];
    }
  } catch (err) {
    console.log(err)
  }
  res.send(item);
});

app.post('/api/face', async (req, res) => {
  let item = 0;
  try {
    const conn = await pool.getConnection();
    const data = await conn.query('INSERT INTO `faces`.`face` (`sync`) VALUES (0)');
    conn.end();
    item = data.insertId;
  } catch (err) {
    console.log(err)
  }
  res.send({
    id: item
  });
});

app.put('/api/face/:id', async (req, res) => {
  try {
    const conn = await pool.getConnection();
    if (req.body.personId) {
      await conn.query('UPDATE `faces`.`face` SET `personId`= ' + req.body.personId + ' WHERE  `id`=' + req.params.id);
    }
    if (req.body.gender) {
      await conn.query('UPDATE `faces`.`face` SET `gender`= ' + req.body.gender + ' WHERE  `id`=' + req.params.id);
    }
    if (req.body.age) {
      await conn.query('UPDATE `faces`.`face` SET `age`= ' + req.body.age + ' WHERE  `id`=' + req.params.id);
    }
    if (req.body.expression) {
      await conn.query('UPDATE `faces`.`face` SET `expression`= ' + req.body.expression + ' WHERE  `id`=' + req.params.id);
    }
    conn.end();
  } catch (err) {
    console.log(err)
  }
  res.send();
});

app.post('/api/person/:id', async (req, res) => {
  let item = 0;
  try {
    const conn = await pool.getConnection();
    const data = await conn.query('INSERT INTO `faces`.`person` (`data`) VALUES (0)');
    await conn.query('UPDATE `faces`.`face` SET `personId`= ' + data.insertId + ' WHERE  `id`=' + req.params.id);
    conn.end();
    item = data.insertId;
  } catch (err) {
    console.log(err)
  }
  res.send({
    id: item
  });
});

app.listen(application.port, application.host, () => console.log(`app its start on ${application.port}`));