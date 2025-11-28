-- ==================== DATABASE CREATION ====================
-- Create database basmacrud
CREATE DATABASE IF NOT EXISTS basmacrud;
USE basmacrud;

-- ==================== TABLES ====================

-- Table evenements (without FK constraints)
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
    status ENUM('pending','approved','rejected','cancelled') DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date (date),
    INDEX idx_created_by (created_by)
);

-- Table reservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    seats INT DEFAULT 1,
    status ENUM('confirmed','pending','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES evenements(id) ON DELETE CASCADE,
    INDEX idx_event_id (event_id),
    INDEX idx_user_id (user_id)
);

-- ==================== DATA EXAMPLES ====================

-- Sample events
INSERT INTO evenements (title, description, date, location, capacity, category, created_by, status) VALUES
('Conférence sur l\'IA', 'Une conférence passionnante sur l\'intelligence artificielle', '2025-12-15 14:00:00', 'Amphithéâtre A', 100, 'Conférence', 1, 'approved'),
('Tournoi de Programmation', 'Participez à notre tournoi de programmation avec des prix à la clé !', '2025-12-20 10:00:00', 'Salle Informatique', 30, 'Autre', 1, 'approved'),
('Atelier Développement Web', 'Apprenez les bases du développement web moderne', '2025-12-18 15:00:00', 'Salle B101', 50, 'Atelier', 1, 'approved'),
('Débat Étudiants', 'Un débat ouvert sur les enjeux de l\'éducation', '2025-12-22 16:00:00', 'Amphithéâtre B', 80, 'Autre', 1, 'approved'),
('Déjeuner Réseau', 'Rencontrez d\'autres étudiants et professionnels', '2025-12-25 12:00:00', 'Cafétéria', 150, 'Autre', 1, 'approved');

-- Sample reservations
INSERT INTO reservations (event_id, user_id, name, email, seats, status) VALUES
(1, NULL, 'Étudiant 1', 'etudiant1@example.com', 1, 'confirmed'),
(1, NULL, 'Étudiant 2', 'etudiant2@example.com', 2, 'confirmed'),
(2, NULL, 'Participant X', 'participant@example.com', 1, 'pending'),
(3, NULL, 'Invité', 'invite@example.com', 1, 'confirmed'),
(4, NULL, 'Débatteur A', 'debatteur@example.com', 1, 'confirmed'),
(5, NULL, 'Professionnel Z', 'pro@example.com', 3, 'pending');
