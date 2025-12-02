<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Accès restreint</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body { background: #f6f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .gate-card { background: #fff; padding: 32px; border-radius: 14px; box-shadow: 0 25px 60px rgba(0,0,0,0.08); width: 360px; text-align: center; }
        .gate-card h1 { margin-top: 0; color: #111; }
        .gate-card p { color: #555; }
        .gate-card input[type="password"] { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,0.15); margin-bottom: 16px; }
        .gate-card button { border: none; background: #ff5fa2; color: white; padding: 12px 18px; border-radius: 10px; cursor: pointer; font-weight: 600; width: 100%; }
        .gate-card .error { color: #c0392b; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="gate-card">
        <h1>Espace protégé</h1>
        <p>Veuillez entrer la clé administrateur pour accéder au backoffice.</p>
        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="index.php">
            <input type="hidden" name="area" value="admin" />
            <input type="hidden" name="next" value="<?php echo htmlspecialchars($nextQuery ?? $_SERVER['QUERY_STRING']); ?>" />
            <input type="password" name="admin_key" placeholder="Clé d'accès" />
            <button type="submit">Entrer</button>
        </form>
    </div>
</body>
</html>
