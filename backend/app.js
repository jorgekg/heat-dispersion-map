const express = require('express');
const mariadb = require('mariadb');
const fs = require('fs');
const cors = require('cors');

let json = fs.readFileSync('../application.json');
let application = JSON.parse(json);

const app = express();
app.use(cors());

const pool = mariadb.createPool({
    host: application.db.host,
    user: application.db.user,
    password: application.db.password,
    database: 'faces',
    connectionLimit: 5
});

app.get('/person', async (req, res) => {
    try {
        const connection = await pool.getConnection();
        const [person] = await connection.query('SELECT COUNT(id) count FROM person');
        connection.end();
        res.send(person);
    } catch (err) {
        console.log(err);
    }
});

app.get('/faces', async (req, res) => {
    try {
        const connection = await pool.getConnection();
        const [faces] = await connection.query(
            `SELECT COUNT(personId) count FROM face
            WHERE personId != -1`
        );
        connection.end();
        res.send(faces);
    } catch (err) {
        console.log(err);
    }
});

app.get('/gender', async (req, res) => {
    const connection = await pool.getConnection();
    const gender = await connection.query(
        `SELECT ROUND((COUNT(t0.gender) * 100) / t1.total_person) percent, gender FROM face t0
        INNER JOIN (SELECT COUNT(*) AS total_person FROM face ta0 WHERE ta0.gender != '-1') t1
        WHERE t0.gender != '-1'
        GROUP BY t0.gender`
    );
    connection.end();
    res.send(gender);
});

app.get('/age', async (req, res) => {
    const connection = await pool.getConnection();
    const [age] = await connection.query(
        `SELECT ROUND(SUM(t0.age) / t1.total_age) AS age FROM face t0
        INNER JOIN (SELECT COUNT(*) total_age FROM face ta0 where ta0.age != -1) t1
        WHERE t0.age != -1`
    );
    connection.end();
    res.send(age);
});

app.get('/age_all', async (req, res) => {
    const connection = await pool.getConnection();
    const age = await connection.query(
        `SELECT COUNT(t0.age) total, ROUND(t0.age) idade FROM face t0
        WHERE t0.age != -1
        GROUP BY ROUND(t0.age)`
    );
    connection.end();
    res.send(age);
});

app.get('/feedback', async (req, res) => {
    const connection = await pool.getConnection();
    const [feedback] = await connection.query(
        `SELECT COUNT(t0.expression) AS total, t0.expression FROM face t0
        WHERE t0.expression != '-1'
        GROUP BY t0.expression
        ORDER BY total DESC
        LIMIT 1`
    );
    connection.end();
    res.send(feedback);
});

app.get('/expression', async (req, res) => {
    const connection = await pool.getConnection();
    const expression = await connection.query(
        `SELECT ROUND((COUNT(t0.expression) * 100) / t1.total_expression) AS percent, t0.expression FROM face t0
        INNER JOIN (SELECT COUNT(*) total_expression FROM face ta0 WHERE ta0.expression  != '-1') t1
        WHERE t0.expression != '-1'
        GROUP BY t0.expression`
    );
    connection.end();
    res.send(expression);
});

app.listen(3000, () => console.log('Application has started on port 3000'));