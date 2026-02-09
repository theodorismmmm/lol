<?php
// Simple password protection
// IMPORTANT: Change this password before deploying!
$admin_password = 'valentinstag2026';

session_start();

if (!isset($_SESSION['admin_authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_authenticated'] = true;
        } else {
            $error = 'Falsches Passwort!';
        }
    }
    
    if (!isset($_SESSION['admin_authenticated'])) {
        ?>
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <title>Admin Login</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .login-box {
                    background: white;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                    text-align: center;
                }
                h2 { color: #667eea; margin-bottom: 20px; }
                input[type="password"] {
                    width: 250px;
                    padding: 10px;
                    margin: 10px 0;
                    border: 2px solid #667eea;
                    border-radius: 5px;
                }
                button {
                    padding: 10px 30px;
                    background: #667eea;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .error { color: red; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>🔒 Admin Login</h2>
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="post">
                    <input type="password" name="password" placeholder="Passwort" required>
                    <br>
                    <button type="submit">Anmelden</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gesendete Nachrichten</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            min-width: 200px;
            margin: 10px;
        }
        
        .stat-box h3 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        
        .stat-box p {
            font-size: 1.1em;
        }
        
        .message-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .message-to {
            font-weight: bold;
            color: #667eea;
            font-size: 1.1em;
        }
        
        .message-date {
            color: #666;
            font-size: 0.9em;
        }
        
        .message-body {
            background: white;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.6;
        }
        
        .message-ip {
            color: #999;
            font-size: 0.8em;
            margin-top: 10px;
        }
        
        .no-messages {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 1.2em;
        }
        
        .refresh-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        
        .refresh-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Gesendete Valentinstag-Nachrichten</h1>
        
        <div class="stats">
            <div class="stat-box">
                <h3 id="totalMessages">0</h3>
                <p>Gesamt Nachrichten</p>
            </div>
            <div class="stat-box">
                <h3 id="todayMessages">0</h3>
                <p>Heute gesendet</p>
            </div>
        </div>
        
        <button class="refresh-btn" onclick="loadMessages()">🔄 Aktualisieren</button>
        
        <div id="messagesContainer"></div>
    </div>
    
    <script>
        async function loadMessages() {
            try {
                const response = await fetch('messages.json');
                
                if (!response.ok) {
                    document.getElementById('messagesContainer').innerHTML = 
                        '<div class="no-messages">Noch keine Nachrichten vorhanden.</div>';
                    return;
                }
                
                const messages = await response.json();
                
                if (!messages || messages.length === 0) {
                    document.getElementById('messagesContainer').innerHTML = 
                        '<div class="no-messages">Noch keine Nachrichten vorhanden.</div>';
                    return;
                }
                
                // Calculate stats
                const today = new Date().toISOString().split('T')[0];
                const todayCount = messages.filter(m => m.timestamp.startsWith(today)).length;
                
                document.getElementById('totalMessages').textContent = messages.length;
                document.getElementById('todayMessages').textContent = todayCount;
                
                // Display messages (newest first)
                const container = document.getElementById('messagesContainer');
                container.innerHTML = '';
                
                messages.reverse().forEach((msg, index) => {
                    const card = document.createElement('div');
                    card.className = 'message-card';
                    card.innerHTML = `
                        <div class="message-header">
                            <div class="message-to">📧 An: ${escapeHtml(msg.to)}</div>
                            <div class="message-date">📅 ${msg.timestamp}</div>
                        </div>
                        <div class="message-body">${escapeHtml(msg.message)}</div>
                        <div class="message-ip">🌐 IP: ${msg.ip || 'unbekannt'}</div>
                    `;
                    container.appendChild(card);
                });
            } catch (error) {
                document.getElementById('messagesContainer').innerHTML = 
                    '<div class="no-messages">Fehler beim Laden der Nachrichten: ' + error.message + '</div>';
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Load messages on page load
        loadMessages();
        
        // Auto-refresh every 30 seconds
        setInterval(loadMessages, 30000);
    </script>
</body>
</html>
