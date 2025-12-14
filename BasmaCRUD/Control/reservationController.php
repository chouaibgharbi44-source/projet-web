<?php
require_once __DIR__ . '/../Model/Reservation.php';
require_once __DIR__ . '/../Model/Evenement.php';

class ReservationController {
    private $model;
    private $eventModel;
    private $area;

    public function __construct() {
        $this->model = new Reservation();
        $this->eventModel = new Evenement();
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
        $reservations = $this->model->getAll();
        // for front area make sure the events list is available for the reservation form
        $events = $this->eventModel->getAll();
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/reservations/list.php';
        } else {
            // for frontoffice, we might want to show a page listing reservations or show a per-event reservation modal
            include __DIR__ . '/../View/frontoffice/reservations/index.php';
        }
    }

    public function addForm() {
        $events = $this->eventModel->getAll();
        $selectedEventId = $_GET['event_id'] ?? null;
        $reservations = $this->model->getAll();
        if ($this->area === 'admin') {
            include __DIR__ . '/../View/backoffice/reservations/add.php';
        } else {
            include __DIR__ . '/../View/frontoffice/reservations/index.php';
        }
    }

    public function store() {
        $data = [
            'event_id' => $_POST['event_id'] ?? null,
            'user_id' => $_POST['user_id'] ?? null,
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'seats' => $_POST['seats'] ?? 1,
            'status' => $_POST['status'] ?? 'pending'
        ];

        $this->model->create($data);

        $redirect = 'index.php?entity=reservation';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function editForm() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $redirect = 'index.php?entity=reservation';
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect);
            exit;
        }
        // Frontend users cannot edit, redirect to list
        if ($this->area !== 'admin') {
            header('Location: index.php?entity=reservation');
            exit;
        }
        $reservation = $this->model->getById($id);
        $events = $this->eventModel->getAll();
        include __DIR__ . '/../View/backoffice/reservations/edit.php';
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $redirect = 'index.php?entity=reservation';
            if ($this->area === 'admin') $redirect .= '&area=admin';
            header('Location: ' . $redirect);
            exit;
        }

        $data = [
            'event_id' => $_POST['event_id'] ?? null,
            'user_id' => $_POST['user_id'] ?? null,
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'seats' => $_POST['seats'] ?? 1,
            'status' => $_POST['status'] ?? 'pending'
        ];

        $this->model->update($id, $data);

        $redirect = 'index.php?entity=reservation';
        if ($this->area === 'admin') $redirect .= '&area=admin';
        header('Location: ' . $redirect);
        exit;
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        // Frontend users cannot delete, redirect to list
        if ($this->area !== 'admin') {
            header('Location: index.php?entity=reservation');
            exit;
        }
        if ($id) {
            $this->model->delete($id);
        }
        $redirect = 'index.php?entity=reservation&area=admin';
        header('Location: ' . $redirect);
        exit;
    }
}
