/*
    Comando iniciar MariaDB
    "C:\xampp\mysql\bin\mysql.exe" -u root
*/

CREATE DATABASE webmotors;

USE webmotors;

/* Tabelas */
CREATE TABLE users (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(60),
    gender ENUM('Masculino', 'Feminino', 'Outro'),
    level VARCHAR(3)
);


CREATE TABLE anuncios (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(30),
    marca VARCHAR(30),
    ano VARCHAR(4),
    quilometragem INT(7),
    imagem VARCHAR(255),
    flg_situacao BOOLEAN,
    user_id INT(6),
    FOREIGN KEY (user_id) REFERENCES users(id)
);


/* Inserts users */

-- senha: admin
INSERT INTO users (username, password, phone, email, gender, level) 
    VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', '41987654321', 'admin@teste.com', 'Outro', 'ADM'); 

-- senha: 123
INSERT INTO users (username, password, phone, email, gender, level) 
    VALUES ('Alyson', '202cb962ac59075b964b07152d234b70', '41987654321', 'admin@teste.com', 'Masculino', 'USR');

-- senha: 123
INSERT INTO users (username, password, phone, email, gender, level) 
    VALUES ('Karol', '202cb962ac59075b964b07152d234b70', '41987654321', 'admin@teste.com', 'Feminino', 'USR');

