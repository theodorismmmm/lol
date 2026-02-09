# Valentinstag - Anonyme Nachricht senden 💌

Eine 100% anonyme Valentinstag-Nachrichtenplattform auf Deutsch.

## 🎯 Funktionen

- ✅ Komplett auf Deutsch
- ✅ 100% anonyme Nachrichten
- ✅ Keine Registrierung erforderlich
- ✅ Speicherung aller Nachrichten in `messages.json`
- ✅ Admin-Panel zum Anzeigen aller gesendeten Nachrichten
- ✅ Responsive Design für mobile Geräte
- ✅ E-Mail-Versand (wenn Server konfiguriert)

## 📁 Dateien

- `index.html` - Hauptseite zum Senden anonymer Nachrichten
- `send.php` - Backend zum Verarbeiten und Speichern von Nachrichten
- `admin.php` - Admin-Panel zum Anzeigen aller gesendeten Nachrichten
- `messages.json` - Speicherdatei für alle Nachrichten

## 🚀 Installation

### Option 1: Lokaler PHP-Server (Testen)

```bash
php -S localhost:8000
```

Dann öffne http://localhost:8000 im Browser.

### Option 2: Web-Hosting

1. Lade alle Dateien auf deinen Webserver hoch (über FTP oder cPanel)
2. Stelle sicher, dass PHP aktiviert ist
3. Stelle sicher, dass `messages.json` beschreibbar ist:
   ```bash
   chmod 666 messages.json
   ```
4. Öffne deine Domain im Browser

## 📧 E-Mail-Konfiguration

Die `send.php` Datei verwendet die PHP `mail()` Funktion. Für zuverlässigeren E-Mail-Versand:

### Option 1: Server-SMTP konfigurieren
Konfiguriere deinen Server mit einem SMTP-Server (z.B. über cPanel oder Plesk).

### Option 2: PHPMailer verwenden (empfohlen)
Für Produktionsumgebungen empfehlen wir PHPMailer mit einem E-Mail-Dienst wie:
- SendGrid
- Mailgun
- Amazon SES
- SMTP2GO

Beispiel mit PHPMailer:
```bash
composer require phpmailer/phpmailer
```

## 🔐 Sicherheit

- Alle Eingaben werden validiert und gesäubert
- E-Mail-Adressen werden validiert
- IP-Adressen werden zur Missbrauchsvermeidung gespeichert
- Die Nachrichten werden in `messages.json` gespeichert

## 📊 Admin-Panel

Öffne `admin.php` im Browser, um alle gesendeten Nachrichten anzusehen:
- Zeigt alle Nachrichten mit Zeitstempel
- Statistiken über Gesamtnachrichten und heutige Nachrichten
- Auto-Refresh alle 30 Sekunden
- **Passwortgeschützt** - Standard-Passwort: `valentinstag2026`

**Wichtig:** Ändere das Admin-Passwort in `admin.php` (Zeile 4) vor dem Deployment!

### Admin-Panel Passwort ändern

Bearbeite `admin.php` und ändere diese Zeile:
```php
$admin_password = 'valentinstag2026'; // Ändere dies zu einem sicheren Passwort!
```

### Zusätzlicher Schutz (optional)

Für zusätzliche Sicherheit kannst du auch HTTP Basic Authentication verwenden.

Erstelle eine `.htaccess` Datei:
```apache
<Files "admin.php">
    AuthType Basic
    AuthName "Admin Bereich"
    AuthUserFile /path/to/.htpasswd
    Require valid-user
</Files>
```

Erstelle dann eine `.htpasswd` Datei:
```bash
htpasswd -c .htpasswd admin
```

## 🎨 Anpassung

Du kannst die Farben und das Design in den `<style>` Bereichen der HTML-Dateien anpassen:
- `index.html` - Hauptseite
- `admin.php` - Admin-Panel

## 📝 Datenschutz

Diese Anwendung speichert:
- Empfänger-E-Mail-Adresse
- Nachrichtentext
- Zeitstempel
- IP-Adresse des Absenders (für Missbrauchsvermeidung)

Der Absender bleibt jedoch anonym - keine Namen, E-Mails oder andere identifizierende Informationen werden erfasst.

## ⚠️ Hinweise

1. Die `messages.json` Datei kann groß werden - plane regelmäßiges Archivieren ein
2. Schütze die `admin.php` Datei mit einem Passwort
3. Für Produktionsumgebungen empfehlen wir professionelle E-Mail-Dienste statt `mail()`
4. Implementiere Rate-Limiting, um Spam zu vermeiden
5. Backup regelmäßig die `messages.json` Datei

## 📱 Browser-Unterstützung

- Chrome (empfohlen)
- Firefox
- Safari
- Edge
- Mobile Browser

## 🐛 Fehlerbehebung

### E-Mails werden nicht versendet
- Überprüfe, ob der PHP `mail()` Befehl auf deinem Server funktioniert
- Prüfe die Server-Logs für Fehler
- Erwäge die Verwendung von PHPMailer mit SMTP

### "Permission denied" Fehler
```bash
chmod 666 messages.json
```

### Admin-Panel zeigt keine Nachrichten
- Stelle sicher, dass `messages.json` existiert und lesbar ist
- Überprüfe die Browser-Konsole auf Fehler

## 📄 Lizenz

Freie Nutzung für persönliche und kommerzielle Projekte.

## ❤️ Happy Valentine's Day!

Verbreite Liebe, anonym! 💕