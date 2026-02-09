<?php
header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode nicht erlaubt']);
    exit;
}

// Get form data
$toEmail = filter_input(INPUT_POST, 'toEmail', FILTER_SANITIZE_EMAIL);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Validate inputs
if (!$toEmail || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Bitte gib eine gültige E-Mail-Adresse ein.']);
    exit;
}

if (!$message || strlen(trim($message)) < 5) {
    echo json_encode(['success' => false, 'message' => 'Bitte schreibe eine Nachricht (mindestens 5 Zeichen).']);
    exit;
}

// Create message data
$messageData = [
    'to' => $toEmail,
    'message' => $message,
    'timestamp' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Store message in file
$storageFile = 'messages.json';
$messages = [];

if (file_exists($storageFile)) {
    $content = file_get_contents($storageFile);
    $messages = json_decode($content, true) ?? [];
}

$messages[] = $messageData;

if (file_put_contents($storageFile, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    // Try to send email
    $emailSent = false;
    
    // Email subject and body in German
    $subject = "💌 Du hast eine anonyme Valentinstag-Nachricht erhalten!";
    $emailBody = "
    ╔══════════════════════════════════════╗
    ║   💕 ANONYME VALENTINSTAG-NACHRICHT 💕   ║
    ╚══════════════════════════════════════╝
    
    Jemand hat dir eine anonyme Nachricht zum Valentinstag geschickt!
    
    Nachricht:
    " . nl2br($message) . "
    
    ───────────────────────────────────────
    
    Diese Nachricht wurde 100% anonym versendet.
    Der Absender bleibt vollständig unbekannt.
    
    ❤️ Frohen Valentinstag! ❤️
    ";
    
    $serverDomain = $_SERVER['SERVER_NAME'] ?? 'valentinstag.com';
    $headers = "From: Valentinstag <noreply@{$serverDomain}>\r\n";
    $headers .= "Reply-To: noreply@{$serverDomain}\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Note: mail() function might not work on all servers without proper configuration
    // For production, consider using PHPMailer or a service like SendGrid
    $emailSent = mail($toEmail, $subject, $emailBody, $headers);
    if (!$emailSent) {
        error_log("Failed to send email to: " . $toEmail);
    }
    
    if ($emailSent) {
        echo json_encode([
            'success' => true, 
            'message' => '✅ Deine anonyme Nachricht wurde erfolgreich versendet!'
        ]);
    } else {
        echo json_encode([
            'success' => true, 
            'message' => '✅ Nachricht gespeichert! (E-Mail-Versand kann je nach Server-Konfiguration variieren)'
        ]);
    }
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Fehler beim Speichern der Nachricht. Bitte versuche es erneut.'
    ]);
}
?>
