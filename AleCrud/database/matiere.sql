-- Create database and table for matiere (adjust names if needed)
CREATE DATABASE IF NOT EXISTS alecrud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alecrud;

CREATE TABLE IF NOT EXISTS matiere (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom_matiere VARCHAR(255) NOT NULL,
  titre VARCHAR(255) NOT NULL,
  description TEXT,
  date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
  niveau_difficulte VARCHAR(50)
);

-- Sample data
INSERT INTO matiere (nom_matiere, titre, description, niveau_difficulte) VALUES
('Maths','Algèbre de base','Introduction aux équations','Facile'),
('Physique','Mécanique','Forces et mouvements','Moyen');
