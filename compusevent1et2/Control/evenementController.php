<?php
require_once __DIR__ . '/../Model/Evenement.php';

class EvenementController {
    private $model;
    private $area;

    public function __construct() {
        $this->model = new Evenement();
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
        $evenements = $this->model->getAll();
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
        // For simplicity, created_by uses a default value
        // Parse date text (user-entered) to SQL DATETIME if possible
        $dateRaw = $_POST['date'] ?? date('Y-m-d H:i:s');
        if (strpos($dateRaw, 'T') !== false) $dateRaw = str_replace('T', ' ', $dateRaw);
        $dateRaw = date('Y-m-d H:i:s', strtotime($dateRaw));

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $dateRaw,
            'location' => $_POST['location'] ?? '',
            'capacity' => $_POST['capacity'] ?? null,
            'category' => $_POST['category'] ?? 'Autre',
            'created_by' => $_POST['created_by'] ?? 1,
            'image' => $_POST['image'] ?? null,
            'status' => $_POST['status'] ?? 'approved'
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
        $evenement = $this->model->getById($id);
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

        // Parse date text (user-entered) to SQL DATETIME if possible
        $dateRaw = $_POST['date'] ?? date('Y-m-d H:i:s');
        if (strpos($dateRaw, 'T') !== false) $dateRaw = str_replace('T', ' ', $dateRaw);
        $dateRaw = date('Y-m-d H:i:s', strtotime($dateRaw));

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'date' => $dateRaw,
            'location' => $_POST['location'] ?? '',
            'capacity' => $_POST['capacity'] ?? null,
            'category' => $_POST['category'] ?? 'Autre',
            'image' => $_POST['image'] ?? null,
            'status' => $_POST['status'] ?? 'approved'
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
