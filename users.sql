

CREATE DATABASE IF NOT EXISTS infotech;

-- Création d'un utilisateur mysql 
CREATE USER IF NOT EXISTS student@localhost IDENTIFIED BY "password"; 

-- On donne les droits à l'utilisateur student sur tous les objets de la base infotech
GRANT ALL ON infotech.* TO student@localhost; 

USE infotech; 

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    email VARCHAR(255) UNIQUE, 
    password VARCHAR(255)
); 