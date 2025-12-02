<?php
require_once __DIR__ . '/../Model/Ressource.php';
require_once __DIR__ . '/../Model/Matiere.php';

class RessourceController {
    private $model;
    private $matiereModel;
    private $area;

    public function __construct() {
        $this->model = new Ressource();
        $this->matiereModel = new Matiere();
        $this->area = isset($_REQUEST['area']) && $_REQUEST['area'] === 'admin' ? 'admin' : 'front';
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
            default:
                $this->list();
                break;
        }
    }

    public function list() {
        if ($this->area === 'admin') {
            // Allow admin to filter ressources by matiere
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
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type_ressource' => $_POST['type_ressource'] ?? '',
            'url' => $_POST['url'] ?? '',
            'auteur' => $_POST['auteur'] ?? '',
            'date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d H:i:s')
        ];

        if (!$data['matiere_id']) {
            $redirect = 'index.php?entity=ressource';
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect);
            exit;
        }

        $this->model->create($data);
        $redirect = 'index.php?entity=ressource';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        elseif (!empty($_POST['matiere_id'])) $redirect .= '&matiere_id=' . (int)$_POST['matiere_id'];
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
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? '',
            'type_ressource' => $_POST['type_ressource'] ?? '',
            'url' => $_POST['url'] ?? '',
            'auteur' => $_POST['auteur'] ?? '',
            'date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d H:i:s')
        ];

        $this->model->update($id, $data);
        $redirect = 'index.php?entity=ressource';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        elseif (!empty($_POST['matiere_id'])) $redirect .= '&matiere_id=' . (int)$_POST['matiere_id'];
        header('Location: ' . $redirect);
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        $redirect = 'index.php?entity=ressource';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        header('Location: ' . $redirect);
        exit;
    }
}
