<?php
// Basic dispatcher: choose which controller to load based on ?entity=reservation|evenement
$entity = $_GET['entity'] ?? 'evenement';
if ($entity === 'reservation') {
	$controllerFile = __DIR__ . '/Control/reservationController.php';
	require_once $controllerFile;
	$controller = new ReservationController();
} else {
	$controllerFile = __DIR__ . '/Control/evenementController.php';
	require_once $controllerFile;
	$controller = new EvenementController();
}

$controller->handleRequest();
