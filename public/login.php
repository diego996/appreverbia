<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Reverbia • Login', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --bg: #050505;
            --card: #0e0e0f;
            --accent: #8df968;
            --text: #e5e5e5;
            --muted: #9ea0a3;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 20% 20%, rgba(141,249,104,0.08), transparent 35%),
                        radial-gradient(circle at 80% 10%, rgba(141,249,104,0.08), transparent 25%),
                        var(--bg);
            color: var(--text);
            font-family: 'Montserrat', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .panel {
            width: min(420px, 100%);
            background: var(--card);
            padding: 32px 28px;
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.04);
        }
        .logo {
            text-align: center;
            letter-spacing: 6px;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .logo span {
            color: var(--accent);
        }
        h1 {
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #cfd2d5;
            margin: 0 0 24px;
        }
        .field {
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #cfd2d5;
            font-size: 13px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: #151516;
            border: 1px solid #1f1f21;
            border-radius: 12px;
            color: #fff;
            padding: 14px 14px;
            font-size: 14px;
            outline: none;
        }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(141,249,104,0.25);
        }
        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0 14px;
            color: var(--muted);
            font-size: 13px;
        }
        .checkbox input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
        }
        .btn {
            width: 100%;
            background: linear-gradient(90deg, #8df968, #47e361);
            color: #041005;
            font-weight: 700;
            font-size: 15px;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            box-shadow: 0 14px 30px rgba(71,227,97,0.35);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 16px 36px rgba(71,227,97,0.45);} 
        .btn:active { transform: translateY(0); }
        .links {
            text-align: center;
            color: var(--muted);
            margin-top: 18px;
            font-size: 13px;
            line-height: 1.7;
        }
        .links a { color: var(--accent); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="panel" aria-label="Accesso clienti Reverbia">
        <div class="logo" aria-hidden="true">RE<span>V</span>ER<span>B</span>IA</div>
        <h1>I tuoi Personal Trainer a Milano</h1>
        <form action="/app_test/home" method="get">
            <div class="field">
                <label for="email">Inserisci la tua email</label>
                <input type="email" id="email" name="email" placeholder="nome@domain.com" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="checkbox">
                <input type="checkbox" id="robot" required>
                <label for="robot">Non sono un robot</label>
            </div>
            <div class="checkbox">
                <input type="checkbox" id="terms" required>
                <label for="terms">Accetto termini e condizioni</label>
            </div>
            <button class="btn" type="submit">Invia ora</button>
        </form>
        <div class="links">
            Non ricordi le tue credenziali?<br>
            Puoi fare un nuovo <a href="#">reset</a> o contattare il <a href="#">supporto</a>.
        </div>
    </div>
</body>
</html>
