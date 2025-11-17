-- SQL: table `evenements` only (no foreign keys)
-- Use this if you don't want foreign key constraints on created_by
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
    INDEX idx_date (date)
);

-- Exemple de données
INSERT INTO evenements (title, description, date, location, capacity, created_by, status) VALUES
('Conférence sur l\'IA', 'Une conférence sur l\'IA', '2025-12-15 14:00:00', 'Amphithéâtre A', 100, 1, 'approved'),
('Tournoi de Programmation', 'Tournoi', '2025-12-20 10:00:00', 'Salle Informatique', 30, 1, 'approved');
