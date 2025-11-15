-- ==================== DATABASE CREATION ====================
-- Créer la base de données peaceconnect
CREATE DATABASE IF NOT EXISTS peaceconnect;
USE peaceconnect;

-- ==================== TABLES ====================

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
    profile_picture VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table événements
CREATE TABLE IF NOT EXISTS evenements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    capacity INT,
    category VARCHAR(100) DEFAULT 'Autre',
    created_by INT NOT NULL,
    image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_date (date),
    INDEX idx_created_by (created_by)
);

-- Table participants
CREATE TABLE IF NOT EXISTS participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'pending', 'cancelled') DEFAULT 'confirmed',
    FOREIGN KEY (event_id) REFERENCES evenements(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participant (event_id, user_id),
    INDEX idx_user_id (user_id),
    INDEX idx_event_id (event_id)
);

-- Table commentaires (pour les événements)
CREATE TABLE IF NOT EXISTS commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES evenements(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id),
    INDEX idx_user_id (user_id)
);

-- ==================== DONNÉES D'EXEMPLE ====================

-- Insérer des utilisateurs d'exemple
INSERT INTO utilisateurs (name, email, password, role) VALUES
('Étudiant 1', 'etudiant1@example.com', 'password123', 'student'),
('Étudiant 2', 'etudiant2@example.com', 'password123', 'student'),
('Professeur 1', 'prof1@example.com', 'password123', 'teacher'),
('Administrateur', 'admin@example.com', 'password123', 'admin');

-- Insérer des événements d'exemple
INSERT INTO evenements (title, description, date, location, capacity, created_by, status) VALUES
('Conférence sur l\'IA', 'Une conférence passionnante sur les dernières tendances en intelligence artificielle', '2025-12-15 14:00:00', 'Amphithéâtre A', 100, 3, 'approved'),
('Tournoi de Programmation', 'Participez à notre tournoi de programmation avec des prix à la clé !', '2025-12-20 10:00:00', 'Salle Informatique', 30, 3, 'approved'),
('Atelier Développement Web', 'Apprenez les bases du développement web moderne', '2025-12-18 15:00:00', 'Salle B101', 50, 3, 'approved'),
('Débat Étudiants', 'Un débat ouvert sur les enjeux de l\'éducation', '2025-12-22 16:00:00', 'Amphithéâtre B', 80, 1, 'approved'),
('Déjeuner Réseau', 'Rencontrez d\'autres étudiants et professionnels', '2025-12-25 12:00:00', 'Cafétéria', 150, 1, 'approved');

-- Insérer des participants d'exemple
INSERT INTO participants (event_id, user_id, status) VALUES
(1, 1, 'confirmed'),
(1, 2, 'confirmed'),
(2, 1, 'confirmed'),
(3, 2, 'pending'),
(4, 1, 'confirmed'),
(5, 1, 'confirmed'),
(5, 2, 'confirmed');

-- ==================== VIEWS (optionnel) ====================

-- View pour les statistiques des événements
CREATE OR REPLACE VIEW event_statistics AS
SELECT 
    e.id,
    e.title,
    e.date,
    e.location,
    e.capacity,
    COUNT(p.id) as participant_count,
    CASE 
        WHEN e.capacity IS NOT NULL THEN COUNT(p.id) / e.capacity * 100
        ELSE 0 
    END as occupancy_rate
FROM evenements e
LEFT JOIN participants p ON e.id = p.event_id
GROUP BY e.id;
