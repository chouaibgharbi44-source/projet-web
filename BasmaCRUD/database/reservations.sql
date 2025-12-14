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

-- Example reservations
INSERT INTO reservations (event_id, user_id, name, email, seats, status) VALUES
(1, 1, 'Étudiant 1', 'etudiant1@example.com', 1, 'confirmed'),
(1, 2, 'Étudiant 2', 'etudiant2@example.com', 2, 'confirmed'),
(3, NULL, 'Invité', 'invite@example.com', 1, 'pending');
