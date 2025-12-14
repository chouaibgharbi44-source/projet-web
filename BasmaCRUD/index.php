<?php
// Session & admin auth
if (session_status() == PHP_SESSION_NONE)
	session_start();
require_once __DIR__ . '/Control/adminAuth.php';
// Handle admin login/logout actions before any other processing
handleAdminAuthActions();

// If requested, show admin login page (GET)
if (isset($_GET['admin']) && $_GET['admin'] === 'login' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
	require_once __DIR__ . '/View/backoffice/login.php';
	exit;
}

// Basic dispatcher: choose which controller to load based on ?entity=reservation|evenement
$entity = $_GET['entity'] ?? 'evenement';
if ($entity === 'reservation') {
	$controllerFile = __DIR__ . '/Control/reservationController.php';
	require_once $controllerFile;
	$controller = new ReservationController();
} elseif ($entity === 'stats') {
	$controllerFile = __DIR__ . '/Control/statsController.php';
	require_once $controllerFile;
	$controller = new StatsController();
} else {
	$controllerFile = __DIR__ . '/Control/evenementController.php';
	require_once $controllerFile;
	$controller = new EvenementController();
}

// If area=admin, require admin
$area = $_REQUEST['area'] ?? null;
if ($area === 'admin' && !isAdminLogged()) {
	// Redirect to login page. handleAdminAuthActions will handle post.
	header('Location: index.php?admin=login&return=' . urlencode($_SERVER['REQUEST_URI']));
	exit;
}

$controller->handleRequest();
