<?php
// Affiche directement la vue FrontOffice depuis la racine pour éviter les boucles de redirection
require_once __DIR__ . '/View/FrontOffice/evenemnt.php';
exit;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>BasmaEvent - Gestion d'événements</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.5em;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1.05em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
            font-weight: bold;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .feature {
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        
        .feature h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .feature p {
            color: #666;
            font-size: 0.95em;
            line-height: 1.5;
        }
        
        .docs {
            background: #f0f4ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .docs h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .docs-list {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        
        .docs-list li {
            padding: 10px;
            background: white;
            border-left: 3px solid #667eea;
            border-radius: 4px;
        }
        
        .docs-list a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .docs-list a:hover {
            text-decoration: underline;
        }
        
        .footer {
            background: #f9f9f9;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
        }
        
        .icon {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .alert {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert strong {
            color: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 BasmaEvent</h1>
            <p>Système de gestion d'événements - Architecture MVC</p>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>✓ Prérequis :</strong> XAMPP doit être en cours d'exécution (Apache + MySQL)
            </div>
            
            <!-- Section Accès rapide -->
            <div class="section">
                <h2>🚀 Accès rapide</h2>
                
                <div class="buttons-grid">
                    <a href="View/BackOffice/listEvent.php" class="btn btn-primary">
                        👨‍💼 BackOffice (Admin)
                    </a>
                    <a href="View/FrontOffice/listEvent.php" class="btn btn-primary">
                        👥 FrontOffice (Public)
                    </a>
                    <a href="Model/showEvent.php" class="btn btn-secondary">
                        🎓 Démonstration classe
                    </a>
                </div>
            </div>
            
            <!-- Section Caractéristiques -->
            <div class="section">
                <h2>✨ Caractéristiques</h2>
                
                <div class="features">
                    <div class="feature">
                        <div class="icon">🏗️</div>
                        <h3>Architecture MVC</h3>
                        <p>Séparation complète Model-View-Controller</p>
                    </div>
                    
                    <div class="feature">
                        <div class="icon">🔐</div>
                        <h3>Sécurisé</h3>
                        <p>PDO, Protection XSS, Validation stricte</p>
                    </div>
                    
                    <div class="feature">
                        <div class="icon">✅</div>
                        <h3>CRUD complet</h3>
                        <p>Create, Read, Update, Delete fonctionnels</p>
                    </div>
                    
                    <div class="feature">
                        <div class="icon">📝</div>
                        <h3>Validation</h3>
                        <p>Contrôle de saisie sur tous les attributs</p>
                    </div>
                </div>
            </div>
            
            <!-- Section Opérations CRUD -->
            <div class="section">
                <h2>🎯 Opérations CRUD</h2>
                
                <div class="buttons-grid">
                    <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #4CAF50;">
                        <strong style="color: #4CAF50;">➕ CREATE</strong>
                        <p style="margin-top: 5px; color: #666;">Créer un nouvel événement</p>
                    </div>
                    
                    <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #2196F3;">
                        <strong style="color: #2196F3;">👁️ READ</strong>
                        <p style="margin-top: 5px; color: #666;">Afficher la liste et les détails</p>
                    </div>
                    
                    <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #FF9800;">
                        <strong style="color: #FF9800;">✏️ UPDATE</strong>
                        <p style="margin-top: 5px; color: #666;">Modifier un événement existant</p>
                    </div>
                    
                    <div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #f44336;">
                        <strong style="color: #f44336;">🗑️ DELETE</strong>
                        <p style="margin-top: 5px; color: #666;">Supprimer un événement</p>
                    </div>
                </div>
            </div>
            
            <!-- Section Documentation -->
            <div class="section">
                <div class="docs">
                    <h3>📚 Documentation</h3>
                    <ul class="docs-list">
                        <li><a href="README.md">📖 README</a></li>
                        <li><a href="QUICK_START.md">⚡ Démarrage rapide</a></li>
                        <li><a href="ARCHITECTURE.md">🏗️ Architecture</a></li>
                        <li><a href="TESTING_GUIDE.md">🧪 Tests</a></li>
                        <li><a href="IMPLEMENTATION.md">📋 Implémentation</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Section Informations -->
            <div class="section">
                <h2>ℹ️ Informations du projet</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div style="padding: 15px; background: #f9f9f9; border-radius: 4px;">
                        <strong>Architecture</strong>
                        <p style="margin-top: 5px; color: #666;">MVC (Model-View-Controller)</p>
                    </div>
                    
                    <div style="padding: 15px; background: #f9f9f9; border-radius: 4px;">
                        <strong>Langage</strong>
                        <p style="margin-top: 5px; color: #666;">PHP 7.4+ avec PDO</p>
                    </div>
                    
                    <div style="padding: 15px; background: #f9f9f9; border-radius: 4px;">
                        <strong>Base de données</strong>
                        <p style="margin-top: 5px; color: #666;">MySQL/MariaDB (peaceconnect)</p>
                    </div>
                    
                    <div style="padding: 15px; background: #f9f9f9; border-radius: 4px;">
                        <strong>Sécurité</strong>
                        <p style="margin-top: 5px; color: #666;">Prepared Statements, htmlspecialchars</p>
                    </div>
                </div>
            </div>
            
            <!-- Section Guide d'utilisation -->
            <div class="section">
                <h2>📖 Guide d'utilisation</h2>
                
                <ol style="color: #666; line-height: 2; margin-left: 20px;">
                    <li><strong>Démarrez XAMPP</strong> - Apache et MySQL doivent être verts</li>
                    <li><strong>BackOffice</strong> - Allez à http://localhost/BasmaEvent/View/BackOffice/</li>
                    <li><strong>Créez un événement</strong> - Cliquez "Ajouter un événement"</li>
                    <li><strong>Testez CRUD</strong> - Créer, Lire, Modifier, Supprimer</li>
                    <li><strong>FrontOffice</strong> - Consultez la version publique</li>
                </ol>
            </div>
        </div>
        
        <div class="footer">
            <p>✨ BasmaEvent - Gestion d'événements avec Architecture MVC ✨</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                Créé en suivant la méthodologie de cours : <strong>Architecture, Encapsulation, Validation, CRUD, Sécurité</strong>
            </p>
        </div>
    </div>
</body>
</html>
