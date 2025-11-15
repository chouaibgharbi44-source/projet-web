<?php
/**
 * FIXTURES - Données de test pour Campus Connect Events
 * 
 * Ce fichier contient des données de test pour développer et tester l'application
 */

class EventFixtures {
    
    /**
     * Données d'exemple d'événements
     */
    public static function events() {
        return [
            [
                'id' => 1,
                'title' => 'Conférence sur l\'Intelligence Artificielle',
                'description' => 'Une conférence passionnante sur les dernières tendances en IA, avec des experts du domaine. Nous discuterons des applications pratiques de l\'apprentissage automatique et des défis éthiques.',
                'date' => '2025-12-15 14:00:00',
                'location' => 'Amphithéâtre A - Bâtiment Principal',
                'capacity' => 100,
                'created_by' => 3,
                'image' => 'https://via.placeholder.com/400x200?text=AI+Conference',
                'participant_count' => 45,
                'status' => 'approved'
            ],
            [
                'id' => 2,
                'title' => 'Tournoi de Programmation',
                'description' => 'Un tournoi de programmation ouvert à tous les étudiants. Testez vos compétences en codage et remportez des prix attrayants. Les équipes de 2-3 personnes sont les bienvenues.',
                'date' => '2025-12-20 10:00:00',
                'location' => 'Salle Informatique 201',
                'capacity' => 30,
                'created_by' => 3,
                'image' => 'https://via.placeholder.com/400x200?text=Programming+Tournament',
                'participant_count' => 28,
                'status' => 'approved'
            ],
            [
                'id' => 3,
                'title' => 'Atelier Développement Web Moderne',
                'description' => 'Apprenez les technologies web actuelles: React, Node.js, et les meilleures pratiques. Atelier pratique avec exercices en direct.',
                'date' => '2025-12-18 15:00:00',
                'location' => 'Salle Labo B101',
                'capacity' => 50,
                'created_by' => 3,
                'image' => 'https://via.placeholder.com/400x200?text=Web+Development+Workshop',
                'participant_count' => 42,
                'status' => 'approved'
            ],
            [
                'id' => 4,
                'title' => 'Débat sur les Enjeux Éducatifs',
                'description' => 'Un débat ouvert et interactif sur l\'avenir de l\'éducation, l\'impact de la technologie et la formation continue.',
                'date' => '2025-12-22 16:00:00',
                'location' => 'Amphithéâtre B',
                'capacity' => 80,
                'created_by' => 1,
                'image' => 'https://via.placeholder.com/400x200?text=Educational+Debate',
                'participant_count' => 35,
                'status' => 'approved'
            ],
            [
                'id' => 5,
                'title' => 'Déjeuner Réseau Étudiant',
                'description' => 'Rencontrez d\'autres étudiants, des professeurs et des professionnels du secteur. Excellent pour créer des connections et explorer les opportunités.',
                'date' => '2025-12-25 12:00:00',
                'location' => 'Cafétéria Principale',
                'capacity' => 150,
                'created_by' => 1,
                'image' => 'https://via.placeholder.com/400x200?text=Networking+Lunch',
                'participant_count' => 89,
                'status' => 'approved'
            ]
        ];
    }
    
    /**
     * Données d'exemple d'utilisateurs
     */
    public static function users() {
        return [
            [
                'id' => 1,
                'name' => 'Ahmed Souissi',
                'email' => 'etudiant1@example.com',
                'password' => 'password123',
                'role' => 'student'
            ],
            [
                'id' => 2,
                'name' => 'Fatima Ben Ali',
                'email' => 'etudiant2@example.com',
                'password' => 'password123',
                'role' => 'student'
            ],
            [
                'id' => 3,
                'name' => 'Dr. Mohamed Cherif',
                'email' => 'prof1@example.com',
                'password' => 'password123',
                'role' => 'teacher'
            ],
            [
                'id' => 4,
                'name' => 'Administrateur Système',
                'email' => 'admin@example.com',
                'password' => 'password123',
                'role' => 'admin'
            ]
        ];
    }
    
    /**
     * Données d'exemple de participants
     */
    public static function participants() {
        return [
            ['event_id' => 1, 'user_id' => 1],
            ['event_id' => 1, 'user_id' => 2],
            ['event_id' => 2, 'user_id' => 1],
            ['event_id' => 2, 'user_id' => 3],
            ['event_id' => 3, 'user_id' => 2],
            ['event_id' => 4, 'user_id' => 1],
            ['event_id' => 5, 'user_id' => 1],
            ['event_id' => 5, 'user_id' => 2],
        ];
    }
    
    /**
     * Données de test API
     */
    public static function apiTests() {
        return [
            [
                'name' => 'Créer un événement',
                'method' => 'POST',
                'action' => 'create',
                'data' => [
                    'title' => 'Événement Test',
                    'description' => 'Description de test',
                    'date' => '2025-12-30T10:00:00',
                    'location' => 'Salle Test',
                    'capacity' => 50,
                    'created_by' => 1
                ],
                'expected' => 201
            ],
            [
                'name' => 'Lire tous les événements',
                'method' => 'GET',
                'action' => 'readAll',
                'params' => ['filter' => 'all'],
                'expected' => 200
            ],
            [
                'name' => 'Lire un événement',
                'method' => 'GET',
                'action' => 'read',
                'params' => ['id' => 1],
                'expected' => 200
            ],
            [
                'name' => 'Mettre à jour un événement',
                'method' => 'POST',
                'action' => 'update',
                'data' => [
                    'id' => 1,
                    'title' => 'Titre modifié'
                ],
                'expected' => 200
            ],
            [
                'name' => 'Rejoindre un événement',
                'method' => 'POST',
                'action' => 'join',
                'data' => [
                    'event_id' => 1,
                    'user_id' => 1
                ],
                'expected' => 200
            ],
            [
                'name' => 'Quitter un événement',
                'method' => 'POST',
                'action' => 'leave',
                'data' => [
                    'event_id' => 1,
                    'user_id' => 1
                ],
                'expected' => 200
            ],
            [
                'name' => 'Récupérer les participants',
                'method' => 'GET',
                'action' => 'getParticipants',
                'params' => ['id' => 1],
                'expected' => 200
            ],
            [
                'name' => 'Supprimer un événement',
                'method' => 'GET',
                'action' => 'delete',
                'params' => ['id' => 1],
                'expected' => 200
            ]
        ];
    }
    
    /**
     * Données de test de formulaire
     */
    public static function formTests() {
        return [
            [
                'name' => 'Créer avec données valides',
                'valid' => true,
                'data' => [
                    'title' => 'Titre valide',
                    'description' => 'Description valide et détaillée',
                    'date' => '2025-12-30T10:00:00',
                    'location' => 'Lieu valide',
                    'capacity' => 50
                ]
            ],
            [
                'name' => 'Créer avec titre manquant',
                'valid' => false,
                'data' => [
                    'title' => '',
                    'description' => 'Description',
                    'date' => '2025-12-30T10:00:00',
                    'location' => 'Lieu'
                ],
                'error' => 'Title is required'
            ],
            [
                'name' => 'Créer avec date passée',
                'valid' => false,
                'data' => [
                    'title' => 'Titre',
                    'description' => 'Description',
                    'date' => '2020-12-30T10:00:00',
                    'location' => 'Lieu'
                ],
                'error' => 'Date must be in the future'
            ],
            [
                'name' => 'Créer avec capacité invalide',
                'valid' => false,
                'data' => [
                    'title' => 'Titre',
                    'description' => 'Description',
                    'date' => '2025-12-30T10:00:00',
                    'location' => 'Lieu',
                    'capacity' => -5
                ],
                'error' => 'Capacity must be positive'
            ]
        ];
    }
}

/**
 * Classe de test API
 */
class APITester {
    private $base_url;
    private $results = [];
    
    public function __construct($base_url = 'http://localhost/BasmaEvent/controller/EvenementController.php') {
        $this->base_url = $base_url;
    }
    
    public function runTests() {
        $tests = EventFixtures::apiTests();
        
        foreach ($tests as $test) {
            $this->runTest($test);
        }
        
        return $this->results;
    }
    
    private function runTest($test) {
        $result = [
            'name' => $test['name'],
            'passed' => false,
            'message' => ''
        ];
        
        try {
            if ($test['method'] === 'GET') {
                $url = $this->base_url . '?action=' . $test['action'];
                if (isset($test['params'])) {
                    foreach ($test['params'] as $key => $value) {
                        $url .= '&' . $key . '=' . $value;
                    }
                }
                $response = $this->makeRequest('GET', $url);
            } else {
                $response = $this->makeRequest('POST', $this->base_url, $test['data']);
            }
            
            $result['passed'] = true;
            $result['message'] = 'Success';
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
        }
        
        $this->results[] = $result;
    }
    
    private function makeRequest($method, $url, $data = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        if ($method === 'POST' && $data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code >= 400) {
            throw new Exception('HTTP ' . $http_code . ': ' . $response);
        }
        
        return json_decode($response, true);
    }
    
    public function getResults() {
        return $this->results;
    }
}

// Export des fixtures pour utilisation
if (php_sapi_name() === 'cli') {
    // Afficher les fixtures en JSON pour le CLI
    echo json_encode([
        'events' => EventFixtures::events(),
        'users' => EventFixtures::users(),
        'participants' => EventFixtures::participants()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
