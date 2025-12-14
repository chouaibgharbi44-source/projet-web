<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Campus Connect</title>
    <!-- Include main style for fonts -->
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <style>
        /* Override basic resets just in case */
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            background: #fcfcfc;
            /* Simple clean background */
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            /* Prevent content scroll if possible */
        }

        /* Full screen container */
        .login-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            /* darker overlay to make white card pop */
            background: rgba(0, 0, 0, 0.02);
            z-index: 9999;
        }

        /* The Card */
        .login-card-center {
            background: white;
            width: 100%;
            max-width: 500px;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 25px;
            position: relative;
        }

        /* Title */
        .login-title {
            font-size: 32px;
            font-weight: 700;
            color: #7b2da8;
            /* Purple theme */
            margin: 0;
            margin-bottom: 10px;
        }

        /* Form Elements */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
        }

        .login-label {
            font-size: 16px;
            color: #555;
            font-weight: 500;
            text-align: left;
            margin-bottom: -10px;
            margin-left: 5px;
        }

        .login-input-field {
            width: 100%;
            padding: 18px;
            border-radius: 12px;
            border: 2px solid #eee;
            background: #f9f9f9;
            font-size: 18px;
            text-align: center;
            transition: all 0.3s;
            box-sizing: border-box;
            /* Critical for padding */
        }

        .login-input-field:focus {
            outline: none;
            border-color: #ff6fb1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(255, 111, 177, 0.1);
        }

        .login-btn-main {
            width: 100%;
            padding: 20px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #7b2da8, #ff6fb1);
            color: white;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 10px 20px rgba(123, 45, 168, 0.3);
        }

        .login-btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(123, 45, 168, 0.4);
        }

        .login-btn-main:active {
            transform: scale(0.98);
        }

        .error-banner {
            background: #ffe6e6;
            color: #d8000c;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            display: none;
            /* JS or PHP will toggle */
        }

        .back-link-custom {
            margin-top: 15px;
            color: #999;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-link-custom:hover {
            color: #7b2da8;
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card-center">
            <h1 class="login-title">Admin Login</h1>

            <form method="post" action="index.php?admin=login" class="login-form">
                <?php if (!empty($_SESSION['admin_login_error'])): ?>
                    <div class="error-banner" style="display:block;">
                        <?php echo htmlspecialchars($_SESSION['admin_login_error']); ?>
                        <?php unset($_SESSION['admin_login_error']); ?>
                    </div>
                <?php endif; ?>

                <label class="login-label">Mot de passe</label>
                <input type="password" name="admin_password" class="login-input-field" placeholder="••••••••" required
                    autofocus />

                <button type="submit" class="login-btn-main">Se Connecter</button>
            </form>

            <a href="index.php" class="back-link-custom">Retour au site</a>
        </div>
    </div>

</body>

</html>