<?php
/**
 * INSTALLATION ET CONFIGURATION
 *
 * Ce fichier aide à configurer et vérifier l'installation de Campus Connect
 */

// Vérifier que nous ne soyons pas en production
if (!isset($_GET['setup_key']) || $_GET['setup_key'] !== 'admin123') {
    die('Accès refusé');
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Campus Connect - Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FF6B9D, #FFA502);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 100%;
            padding: 2rem;
        }
        h1 {
            color: #FF6B9D;
            margin-bottom: 1rem;
            text-align: center;
        }
        h2 {
            color: #2C3E50;
            font-size: 1.3rem;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .check {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            margin: 0.5rem 0;
            background: #F5F7FA;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }
        .check.error {
            border-left-color: #F44336;
            background: #FFEBEE;
        }
        .check.warning {
            border-left-color: #FFC107;
            background: #FFF3E0;
        }
        .status {
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .status.ok {
            background: #4CAF50;
            color: white;
        }
        .status.error {
            background: #F44336;
            color: white;
        }
        .status.warning {
            background: #FFC107;
            color: #333;
        }
        .info {
            background: #E3F2FD;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .info h3 {
            color: #2196F3;
            margin-bottom: 0.5rem;
        }
        .info p {
            color: #1976D2;
            font-size: 0.9rem;
        }
        .section {
            margin: 2rem 0;
        }
        code {
            background: #F5F7FA;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #FF6B9D;
        }
        .steps {
            list-style: none;
            counter-reset: step-counter;
        }
        .steps li {
            counter-increment: step-counter;
            padding: 1rem;
            margin: 0.5rem 0;
            background: #F5F7FA;
            border-radius: 8px;
            border-left: 4px solid #FF6B9D;
        }
        .steps li::before {
            content: counter(step-counter);
            background: #FF6B9D;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-weight: bold;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            justify-content: center;
        }
        button {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #FF6B9D;
            color: white;
        }
        .btn-primary:hover {
            background: #ff4d7c;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #F5F7FA;
            color: #2C3E50;
            border: 2px solid #FF6B9D;
        }
        .btn-secondary:hover {
            background: #FF6B9D;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Campus Connect - Installation</h1>
        
        <div class="info">
            <h3>Bienvenue sur Campus Connect</h3>
            <p>Ce script d'installation vous aide à vérifier que tout est correctement configuré.</p>
        </div>

        <div class="section">
            <h2>✅ Vérifications système</h2>
            
            <div class="check <?php echo phpversion() >= '7.4' ? '' : 'error'; ?>">
                <span>PHP Version (≥ 7.4)</span>
                <span class="status <?php echo phpversion() >= '7.4' ? 'ok' : 'error'; ?>">
                    <?php echo phpversion(); ?>
                </span>
            </div>

            <div class="check <?php echo extension_loaded('mysqli') ? '' : 'error'; ?>">
                <span>Extension MySQLi</span>
                <span class="status <?php echo extension_loaded('mysqli') ? 'ok' : 'error'; ?>">
                    <?php echo extension_loaded('mysqli') ? 'Activée' : 'Désactivée'; ?>
                </span>
            </div>

            <div class="check <?php echo is_writable(dirname(__FILE__)) ? '' : 'warning'; ?>">
                <span>Permissions d'écriture</span>
                <span class="status <?php echo is_writable(dirname(__FILE__)) ? 'ok' : 'warning'; ?>">
                    <?php echo is_writable(dirname(__FILE__)) ? 'OK' : 'Attention'; ?>
                </span>
            </div>

            <div class="check <?php 
                $conn = @new mysqli('localhost', 'Projet2A', '123', 'peaceconnect');
                $db_ok = !$conn->connect_error;
            ?>">
                <span>Connexion Base de données</span>
                <span class="status <?php echo $db_ok ? 'ok' : 'error'; ?>">
                    <?php echo $db_ok ? 'Connectée' : 'Erreur'; ?>
                </span>
            </div>
        </div>

        <div class="section">
            <h2>📋 Étapes d'installation</h2>
            
            <ol class="steps">
                <li>
                    <strong>Créer la base de données</strong><br>
                    Importer le fichier <code>database.sql</code> dans phpMyAdmin
                </li>
                <li>
                    <strong>Vérifier la configuration</strong><br>
                    Éditer <code>config/config.php</code> avec vos paramètres MySQL
                </li>
                <li>
                    <strong>Accéder à l'application</strong><br>
                    FrontOffice: <code>/FrontOffice/index.html</code><br>
                    BackOffice: <code>/BackOffice/index.html</code>
                </li>
                <li>
                    <strong>Commencer à utiliser</strong><br>
                    Créez des événements et gérez-les facilement !
                </li>
            </ol>
        </div>

        <div class="section">
            <h2>🔗 Accès rapides</h2>
            
            <div class="info">
                <h3>Identifiants par défaut</h3>
                <p><strong>Admin:</strong> admin@example.com / password123</p>
                <p><strong>Étudiant:</strong> etudiant1@example.com / password123</p>
            </div>
        </div>

        <div class="btn-group">
            <a href="../FrontOffice/index.html" class="btn-primary">Accéder à FrontOffice</a>
            <a href="../BackOffice/index.html" class="btn-secondary">Accéder à BackOffice</a>
        </div>

        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #E0E0E0; text-align: center; font-size: 0.9rem; color: #999;">
            <p>Campus Connect - Module Événements v1.0</p>
            <p>Pour supprimer cette page d'installation, supprimez le fichier <code>install.php</code></p>
        </div>
    </div>
</body>
</html>
