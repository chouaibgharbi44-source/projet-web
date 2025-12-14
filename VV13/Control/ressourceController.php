<?php
require_once __DIR__ . '/../Model/Ressource.php';
require_once __DIR__ . '/../Model/Matiere.php';

class RessourceController {
    private $model;
    private $matiereModel;
    private $pdo;
    private $area;

    public function __construct() {
        $this->model = new Ressource();
        $this->matiereModel = new Matiere();
        $this->pdo = $this->matiereModel->getPdo();
        $this->area = isset($_REQUEST['area']) && $_REQUEST['area'] === 'admin' ? 'admin' : 'front';
    }

    private function logAction($entity_type, $entity_id, $entity_title, $action, $fullData = null) {
        $userType = ($this->area === 'admin') ? 'Admin' : 'Utilisateur';
        if (!$entity_id || !is_numeric($entity_id) || $entity_id <= 0) return;

        $dataJson = null;
        if ($action === 'delete' && $fullData) {
            $dataJson = json_encode($fullData, JSON_UNESCAPED_UNICODE);
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO log (user_id, user_type, entity_type, entity_id, entity_title, action, data_json)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([1, $userType, $entity_type, (int)$entity_id, trim($entity_title), $action, $dataJson]);
    }

    public function handleRequest() {
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
            case 'favoris':
                $this->mesFavoris();
                break;
            case 'toggle-favori':
                $this->toggleFavori();
                break;
            case 'partages':
                $this->mesPartages();
                break;
            case 'activites':
                $this->mesActivites();
                break;
            case 'telecharger':
                $this->telecharger();
                break;
            case 'telechargements':
                $this->mesTelechargements(); // ✅ Gère les deux zones
                break;
            case 'restore':
                $this->restore();
                break;
            default:
                $this->list();
                break;
        }
    }

    public function list() {
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

        if ($this->area === 'admin') {
            $matieres = $this->matiereModel->getAll();
            $matiereId = isset($_GET['matiere_id']) ? (int)$_GET['matiere_id'] : null;
            $selectedMatiere = null;
            if ($matiereId) {
                $selectedMatiere = $this->matiereModel->getById($matiereId);
            }
            if ($selectedMatiere) {
                $ressources = $this->model->getByMatiere($matiereId);
            } else {
                $ressources = $this->model->getAll();
            }
            $stats = [
                'perMatiere' => $this->model->countByMatiere(),
                'perType' => $this->model->countByType()
            ];
            include __DIR__ . '/../View/backoffice/ressource_list.php';
        } else {
            $matieres = $this->matiereModel->getAll();
            $matiereId = isset($_GET['matiere_id']) ? (int)$_GET['matiere_id'] : null;
            $selectedMatiere = null;
            $ressources = [];
            if ($matiereId) {
                $selectedMatiere = $this->matiereModel->getById($matiereId);
                if ($selectedMatiere) {
                    $ressources = $this->model->getByMatiere($matiereId);
                }
            }
            $ressourceModel = $this->model;
            include __DIR__ . '/../View/frontoffice/ressource_index.php';
        }
    }

    public function addForm() {
        $matieres = $this->matiereModel->getAll();
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/ressource_add.php';
        } else {
            include __DIR__ . '/../View/frontoffice/ressource_index.php';
        }
    }

    public function store() {
        $data = [
            'matiere_id' => isset($_POST['matiere_id']) ? (int)$_POST['matiere_id'] : null,
            'user_id' => 1,
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type_ressource' => $_POST['type_ressource'] ?? '',
            'url' => $_POST['url'] ?? '',
            'auteur' => $_POST['auteur'] ?? '',
            'date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d H:i:s')
        ];

        if (!$data['matiere_id'] || empty($data['titre'])) {
            $redirect = 'index.php?entity=ressource';
            if ($this->area === 'admin') $redirect .= '&area=admin&action=add';
            header('Location: ' . $redirect . '&error=invalid_data');
            exit;
        }

        $ressource_id = $this->model->create($data);

        if ($ressource_id && is_numeric($ressource_id) && $ressource_id > 0) {
            $this->logAction('ressource', $ressource_id, $data['titre'], 'add');
            $_SESSION['notification'] = [
                'type' => 'success',
                'title' => 'Ressource ajoutée !',
                'message' => 'Votre ressource a été partagée avec succès.',
                'link' => 'index.php?entity=ressource&action=partages',
                'linkText' => 'Voir mes partages'
            ];
            $_SESSION['show_motivation'] = true;
        }

        if ($this->area === 'admin') {
            $redirect = 'index.php?entity=ressource&area=admin';
        } else {
            $redirect = 'index.php?entity=ressource';
            if (!empty($data['matiere_id'])) {
                $redirect .= '&matiere_id=' . $data['matiere_id'];
            }
        }
        header('Location: ' . $redirect);
        exit;
    }

    public function editForm() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $redirect = 'index.php?entity=ressource';
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect);
            exit;
        }
        $ressource = $this->model->getById($id);
        $matieres = $this->matiereModel->getAll();
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/ressource_edit.php';
        } else {
            include __DIR__ . '/../View/frontoffice/ressource_index.php';
        }
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $redirect = 'index.php?entity=ressource';
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect);
            exit;
        }

        $data = [
            'matiere_id' => isset($_POST['matiere_id']) ? (int)$_POST['matiere_id'] : null,
            'user_id' => 1,
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type_ressource' => $_POST['type_ressource'] ?? '',
            'url' => $_POST['url'] ?? '',
            'auteur' => $_POST['auteur'] ?? '',
            'date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d H:i:s')
        ];

        if (empty($data['titre'])) {
            $redirect = "index.php?entity=ressource&action=edit&id=$id";
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect . '&error=invalid_data');
            exit;
        }

        $this->model->update($id, $data);
        $this->logAction('ressource', $id, $data['titre'], 'edit');

        $redirect = 'index.php?entity=ressource';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        elseif (!empty($data['matiere_id'])) $redirect .= '&matiere_id=' . $data['matiere_id'];
        header('Location: ' . $redirect);
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id) || $id <= 0) {
            $redirect = 'index.php?entity=ressource&action=list';
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect);
            exit;
        }

        $ressource = $this->model->getById($id);
        if ($ressource) {
            $this->logAction('ressource', $id, $ressource['titre'], 'delete', $ressource);
        }

        $this->model->delete($id);

        $redirect = 'index.php?entity=ressource&action=list';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function toggleFavori() {
        $ressource_id = $_GET['id'] ?? null;
        if (!$ressource_id || !is_numeric($ressource_id)) {
            header('Location: index.php?entity=ressource');
            exit;
        }

        $user_id = 1;
        if ($this->model->estFavori($ressource_id, $user_id)) {
            $this->model->retirerFavori($ressource_id, $user_id);
        } else {
            $this->model->ajouterFavori($ressource_id, $user_id);
            $_SESSION['notification'] = [
                'type' => 'like',
                'title' => 'Ajouté aux favoris !',
                'message' => 'Cette ressource est maintenant dans vos favoris.',
                'link' => 'index.php?entity=ressource&action=favoris',
                'linkText' => 'Voir mes favoris'
            ];
        }

        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?entity=ressource';
        header('Location: ' . $redirect);
        exit;
    }

    public function mesFavoris() {
        $user_id = 1;
        $ressourceModel = $this->model;

        if ($this->area === 'admin') {
            $ressources = $this->model->getAllAvecFavoris();
            $favoris = array_filter($ressources, fn($r) => $r['nb_favoris'] > 0);
            include __DIR__ . '/../View/backoffice/favoris_list.php';
        } else {
            include __DIR__ . '/../View/frontoffice/favoris_index.php';
        }
    }

    public function mesPartages() {
        if ($this->area === 'admin') {
            $ressources = $this->model->getAll();
            $matieres = $this->matiereModel->getAll();
            include __DIR__ . '/../View/backoffice/partages_list.php';
        } else {
            $user_id = 1;
            $ressources = $this->model->getPartagesParUser($user_id);
            $matieres = $this->matiereModel->getAll();
            $ressourceModel = $this->model;
            include __DIR__ . '/../View/frontoffice/partages_index.php';
        }
    }

    public function mesActivites() {
        $user_id = 1;
        $ressourceModel = $this->model;
        $matiereModel = $this->matiereModel;

        $mesPartages = $ressourceModel->getPartagesParUser($user_id);
        $mesFavoris = $ressourceModel->getFavorisParUser($user_id);
        $mesTelechargements = $ressourceModel->getTelechargementsParUser($user_id);
        $matieres = $matiereModel->getAll();

        include __DIR__ . '/../View/frontoffice/activites_index.php';
        }
    public function telecharger() {
        $ressource_id = $_GET['id'] ?? null;
        if (!$ressource_id || !is_numeric($ressource_id)) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $user_id = 1;
        $this->model->ajouterTelechargement($ressource_id, $user_id);

        
        $_SESSION['notification'] = [
            'type' => 'download',
            'title' => 'Téléchargement réussi !',
            'message' => 'La ressource a été ajoutée à vos téléchargements.',
            'link' => 'index.php?entity=ressource&action=telechargements',
            'linkText' => 'Voir mes téléchargements'
        ];

        $ressource = $this->model->getById($ressource_id);

        if ($ressource && !empty($ressource['url'])) {
            
            header('Location: index.php?entity=ressource&action=telechargement-succes&id=' . $ressource_id);
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit;
    }

    
    public function mesTelechargements() {
        if ($this->area === 'admin') {
            
            $telechargements = $this->model->getAllTelechargements();
            $matieres = $this->matiereModel->getAll();
            include __DIR__ . '/../View/backoffice/telechargements_list.php';
        } else {
            
            $user_id = 1;
            $mesTelechargements = $this->model->getTelechargementsParUser($user_id);
            $matieres = $this->matiereModel->getAll();
            include __DIR__ . '/../View/frontoffice/telechargements_index.php';
        }
    }

    public function restore() {
        $log_id = $_GET['log_id'] ?? null;
        if (!$log_id) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM log WHERE id = ? AND action = 'delete' AND entity_type = 'ressource'");
        $stmt->execute([$log_id]);
        $log = $stmt->fetch();

        if ($log && $log['data_json']) {
            $data = json_decode($log['data_json'], true);
            if ($data) {
                $this->model->create([
                    'matiere_id' => $data['matiere_id'] ?? 1,
                    'user_id' => $data['user_id'] ?? 1,
                    'titre' => $data['titre'] ?? '',
                    'description' => $data['description'] ?? '',
                    'type_ressource' => $data['type_ressource'] ?? '',
                    'url' => $data['url'] ?? '',
                    'auteur' => $data['auteur'] ?? '',
                    'date_ajout' => $data['date_ajout'] ?? date('Y-m-d H:i:s')
                ]);
            }
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}