<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Campus Connect' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<script src="/assets/js/validation.js"></script>
<body>
    <header>
        <h1>CAMPUS CONNECT</h1>
        <p>Your University United</p>
    </header>
    <nav>
    <a href="<?= BASE_URL ?>/">Accueil</a>
    <a href="<?= BASE_URL ?>/admin">Administration</a>
    </nav>
    <div class="container">