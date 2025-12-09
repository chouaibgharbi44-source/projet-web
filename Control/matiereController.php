<?php
require_once __DIR__ . '/../Model/Matiere.php';
require_once __DIR__ . '/../Model/Ressource.php';

class MatiereController {
    private $model;
    private $pdo;
    private $area;
    private $ressourceModel;

    public function __construct() {
        $this->model = new Matiere();
        $this->pdo = $this->model->getPdo();
        $this->area = isset($_REQUEST['area']) && $_REQUEST['area'] === 'admin' ? 'admin' : 'front';
        $this->ressourceModel = new Ressource();
    }

    private function logAction($matiere_id, $action) {
        if ($this->area !== 'admin') return;

        if (!$matiere_id || !is_numeric($matiere_id) || $matiere_id <= 0) {
            return;
        }

        $stmt = $this->pdo->prepare("SELECT nom_matiere FROM matiere WHERE id = ?");
        $stmt->execute([$matiere_id]);
        $matiere = $stmt->fetch(PDO::FETCH_ASSOC);

        $nom_matiere = $matiere ? trim($matiere['nom_matiere']) : 'Inconnu';

        $stmt = $this->pdo->prepare("
            INSERT INTO log (user_id, user_type, entity_type, entity_id, entity_title, action)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $userType = ($this->area === 'admin') ? 'Admin' : 'Utilisateur';
        $stmt->execute([1, $userType, 'matiere', (int)$matiere_id, $nom_matiere, $action]);
    }

    public function handleRequest() {
        if ($this->area === 'admin' && !isset($_GET['action'])) {
            $this->dashboard();
            return;
        }

        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        switch ($action) {
            case 'list':
                $this->list();
                break;
            case 'add':
                $this->addForm();
                break;
            case 'store':
                $this->store();
                break;
            case 'edit':
                $this->editForm();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'logs':
                $this->showLogs();
                break;
            case 'dashboard':
                $this->dashboard();
                break;
            default:
                $this->list();
                break;
        }
    }

    public function list() {
        // ✅ GESTION DES NOTIFICATIONS
        $notification = null;
        $showMotivation = false;
        if (isset($_SESSION['notification'])) {
            $notification = $_SESSION['notification'];
            unset($_SESSION['notification']);
        }
        if (isset($_SESSION['show_motivation'])) {
            $showMotivation = true;
            unset($_SESSION['show_motivation']);
        }

        $matieres = $this->model->getAll();
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/list.php';
        } else {
            include __DIR__ . '/../View/frontoffice/index.php';
        }
    }

    public function addForm() {
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/add.php';
        } else {
            include __DIR__ . '/../View/frontoffice/index.php';
        }
    }

    public function store() {
        $data = [
            'nom_matiere' => $_POST['nom_matiere'] ?? '',
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d H:i:s'),
            'niveau_difficulte' => $_POST['niveau_difficulte'] ?? ''
        ];

        if (empty($data['nom_matiere'])) {
            $redirect = 'index.php?entity=matiere&action=add&area=admin&error=nom_requis';
            header('Location: ' . $redirect);
            exit;
        }

        $matiere_id = $this->model->create($data);

        if ($matiere_id && is_numeric($matiere_id) && $matiere_id > 0) {
            $this->logAction($matiere_id, 'add');
            
            // ✅ NOTIFICATION VIA SESSION
            $_SESSION['notification'] = [
                'type' => 'success',
                'title' => 'Matière ajoutée !',
                'message' => 'Votre matière a été créée avec succès.',
                'link' => 'index.php?entity=matiere&area=admin&action=list',
                'linkText' => 'Voir les matières'
            ];
            $_SESSION['show_motivation'] = true;
        }

        $redirect = 'index.php?entity=matiere&action=list&area=admin';
        header('Location: ' . $redirect);
        exit; // ✅ OBLIGATOIRE
    }

    public function editForm() {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id) || $id <= 0) {
            $redirect = 'index.php?entity=matiere&area=admin';
            header('Location: ' . $redirect);
            exit;
        }
        $matiere = $this->model->getById($id);
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/edit.php';
        } else {
            include __DIR__ . '/../View/frontoffice/index.php';
        }
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        if (!$id || !is_numeric($id) || $id <= 0) {
            $redirect = 'index.php?entity=matiere&area=admin';
            header('Location: ' . $redirect);
            exit;
        }

        $data = [
            'nom_matiere' => $_POST['nom_matiere'] ?? '',
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d H:i:s'),
            'niveau_difficulte' => $_POST['niveau_difficulte'] ?? ''
        ];

        if (empty($data['nom_matiere'])) {
            $redirect = "index.php?entity=matiere&action=edit&id=$id&area=admin&error=nom_requis";
            header('Location: ' . $redirect);
            exit;
        }

        $this->model->update($id, $data);
        $this->logAction($id, 'edit');

        $redirect = 'index.php?entity=matiere&action=list&area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id) || $id <= 0) {
            $redirect = 'index.php?entity=matiere&action=list&area=admin';
            header('Location: ' . $redirect);
            exit;
        }

        $matiere = $this->model->getById($id);
        if ($matiere) {
            $this->logAction($id, 'delete');
        }

        $stmt = $this->pdo->prepare("DELETE FROM ressource WHERE matiere_id = ?");
        $stmt->execute([$id]);

        $this->model->delete($id);

        $redirect = 'index.php?entity=matiere&action=list&area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function showLogs() {
        if ($this->area !== 'admin') {
            header('Location: index.php');
            exit;
        }

        // ✅ FILTRES COMBINÉS (action + titre)
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $conditions = [];
        $params = [];

        // Filtre par type d'action
        if ($filter === 'add') {
            $conditions[] = "action = 'add'";
        } else if ($filter === 'edit') {
            $conditions[] = "action = 'edit'";
        } else if ($filter === 'delete') {
            $conditions[] = "action = 'delete'";
        }

        // Filtre par titre
        if (!empty($search)) {
            $conditions[] = "entity_title LIKE ?";
            $params[] = '%' . $search . '%';
        }

        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        // Construction de la requête
        $sql = "
            SELECT 
                user_type,
                entity_type,
                entity_title,
                action,
                created_at,
                id,
                entity_id
            FROM log
            $whereClause
            ORDER BY created_at DESC
            LIMIT 100
        ";

        // Exécution
        if (empty($params)) {
            $stmt = $this->pdo->query($sql);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
        }
        
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $logs_matieres = array_filter($logs, fn($l) => $l['entity_type'] === 'matiere');
        $logs_ressources = array_filter($logs, fn($l) => $l['entity_type'] === 'ressource');

        include __DIR__ . '/../View/backoffice/logs.php';
    }

    public function dashboard() {
        if ($this->area !== 'admin') {
            header('Location: index.php');
            exit;
        }

        $matiereModel = new Matiere();
        $ressourceModel = new Ressource();

        $matieres = $matiereModel->getAll();
        $ressources = $ressourceModel->getAll();

        $stats = [
            'total_matieres' => count($matieres),
            'total_ressources' => count($ressources),
            'total_downloads' => $ressourceModel->countTotalDownloads(),
            'total_favorites' => $ressourceModel->countTotalFavorites(),
            'ressources_par_type' => $ressourceModel->countByType(),
            'ressources_par_matiere' => $ressourceModel->countByMatiere(),
            'matieres_sans_ressource' => $this->getMatieresSansRessource($ressourceModel),
            'activite_recente' => $this->getActiviteRecente(),
            'top_downloads' => $ressourceModel->getTopDownloads(5),
            'top_favorites' => $ressourceModel->getTopFavorites(5)
        ];

        include __DIR__ . '/../View/backoffice/admin_dashboard.php';
    }

    private function getMatieresSansRessource($ressourceModel) {
        $toutes = $this->model->getAll();
        $avecRessource = $ressourceModel->countByMatiere();
        $idsAvecRessource = array_column($avecRessource, 'matiere_id');
        return array_filter($toutes, function($m) use ($idsAvecRessource) {
            return !in_array($m['id'], $idsAvecRessource);
        });
    }

    private function getActiviteRecente() {
        $stmt = $this->pdo->prepare("
            SELECT DATE(created_at) as jour, COUNT(*) as nb
            FROM log
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY jour ASC
        ");
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $debut = new DateTime('-6 days');
        $stat = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $debut->format('Y-m-d');
            $stat[$date] = 0;
            $debut->modify('+1 day');
        }
        foreach ($logs as $log) {
            $stat[$log['jour']] = (int)$log['nb'];
        }

        return ['labels' => array_keys($stat), 'data' => array_values($stat)];
    }
}