<?php
$controllerFile = __DIR__ . '/Control/evenementController.php';
require_once $controllerFile;
$controller = new EvenementController();
$controller->handleRequest();
