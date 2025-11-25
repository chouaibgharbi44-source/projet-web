-- Create table for ressource entity
USE alecrud;

CREATE TABLE IF NOT EXISTS ressource (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  description TEXT,
  type_ressource VARCHAR(100),
  url VARCHAR(255),
  auteur VARCHAR(255),
  date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Sample data
INSERT INTO ressource (titre, description, type_ressource, url, auteur) VALUES
('Introduction à la Programmation', 'Ce document présente les bases de la programmation en C avec des exemples simples.', 'PDF', 'https://example.com/intro-prog.pdf', 'Sarah B.'),
('Mathématiques - Algèbre', 'Résumé complet du chapitre sur les équations et inéquations du second degré.', 'Document', 'https://example.com/algebre.pdf', 'Karim M.'),
('Web - HTML & CSS', 'Un support clair et bien structuré pour apprendre à créer des pages web simples.', 'Tutoriel', 'https://example.com/html-css.pdf', 'Leila T.');
