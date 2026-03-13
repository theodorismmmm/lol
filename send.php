<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Methode nicht erlaubt']); exit; }
$toEmail = filter_input(INPUT_POST,'toEmail',FILTER_SANITIZE_EMAIL);
$message = filter_input(INPUT_POST,'message',FILTER_SANITIZE_STRING);
if (!$toEmail || !filter_var($toEmail,FILTER_VALIDATE_EMAIL)) { echo json_encode(['success'=>false,'message'=>'Bitte gib eine gültige E-Mail-Adresse ein.']); exit; }
if (!$message || strlen(trim($message)) < 5) { echo json_encode(['success'=>false,'message'=>'Bitte schreibe eine Nachricht (mindestens 5 Zeichen).']); exit; }
$messageData = ['to'=>$toEmail,'message'=>$message,'timestamp'=>date('Y-m-d H:i:s'),'ip'=>$_SERVER['REMOTE_ADDR']??'unknown'];
$storageFile = 'messages.json';
$messages = [];
if (file_exists($storageFile)) { $messages = json_decode(file_get_contents($storageFile),true) ?? []; }
$messages[] = $messageData;
if (file_put_contents($storageFile,json_encode($messages,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE))) {
    $subject = "💌 Du hast eine anonyme Valentinstag-Nachricht erhalten!";
    $emailBody = "╔══════════════════════════════════════╗\n║   💕 ANONYME VALENTINSTAG-NACHRICHT 💕   ║\n╚══════════════════════════════════════╝\n\nJemand hat dir eine anonyme Nachricht zum Valentinstag geschickt!\n\nNachricht:\n".nl2br($message)."\n\n───────────────────────────────────────\n\nDiese Nachricht wurde 100% anonym versendet.\nDer Absender bleibt vollständig unbekannt.\n\n❤️ Frohen Valentinstag! ❤️";
    $domain = $_SERVER['SERVER_NAME']??'valentinstag.com';
    $headers = "From: Valentinstag <noreply@{$domain}>\r\nReply-To: noreply@{$domain}\r\nContent-Type: text/html; charset=UTF-8\r\nX-Mailer: PHP/".phpversion();
    $emailSent = mail($toEmail,$subject,$emailBody,$headers);
    if (!$emailSent) error_log("Failed to send email to: ".$toEmail);
    echo json_encode(['success'=>true,'message'=>$emailSent?'✅ Deine anonyme Nachricht wurde erfolgreich versendet!':'✅ Nachricht gespeichert! (E-Mail-Versand kann je nach Server-Konfiguration variieren)']);
} else {
    echo json_encode(['success'=>false,'message'=>'Fehler beim Speichern der Nachricht. Bitte versuche es erneut.']);
}
?>
