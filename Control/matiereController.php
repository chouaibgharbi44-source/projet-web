<?php
require_once __DIR__ . '/../Model/Matiere.php';

class MatiereController {
    private $model;
    private $area;

    public function __construct() {
        $this->model = new Matiere();
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

        $this->model->create($data);
        $redirect = 'index.php';
        if ($this->area === 'admin') $redirect .= '?area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function editForm() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $redirect = 'index.php';
            if ($this->area === 'admin') $redirect .= '?area=admin';
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
        if (!$id) {
            $redirect = 'index.php';
            if ($this->area === 'admin') $redirect .= '?area=admin';
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

        $this->model->update($id, $data);
        $redirect = 'index.php';
        if ($this->area === 'admin') $redirect .= '?area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        $redirect = 'index.php';
        if ($this->area === 'admin') $redirect .= '?area=admin';
        header('Location: ' . $redirect);
        exit;
    }
}
