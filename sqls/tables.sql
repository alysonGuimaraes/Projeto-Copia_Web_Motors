/*
    Comando iniciar MariaDB
    "C:\xampp\mysql\bin\mysql.exe" -u root
*/

CREATE DATABASE webmotors;

USE webmotors;

CREATE TABLE users (
    id INT(4) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(60),
    gender ENUM('Masculino', 'Feminino', 'Outro'),
    level VARCHAR(3)
);


CREATE TABLE anuncios (
    id INT(4) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    marca VARCHAR(30),
    ano INT,
    quilometragem INT,
    imagem VARCHAR(255),
    situacao BOOLEAN,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);