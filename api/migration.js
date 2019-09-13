const mariadb = require('mariadb');

const fs = require('fs');
const json = fs.readFileSync('../application.json');

let application = JSON.parse(json);

const pool = mariadb.createPool({ host: application.host, user: application.user, connectionLimit: application.connectionLimit });

pool.getConnection().then
    (async conn => {
        await conn.query('DROP DATABASE face')
        console.log('create database');
        await conn.query('CREATE DATABASE face');
        await conn.query('USE face');
        console.log('creating table face_detect');
        await conn.query(`CREATE TABLE face_detect (
            id int auto_increment,
            name varchar(255),
            primary key (id)
        )`);
        console.log('create table face_detect');
    }
).catch(err => console.log(err));