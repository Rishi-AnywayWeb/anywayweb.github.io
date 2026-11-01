<?php
// Contact form handler. Sends via authenticated SMTP (recommended by Infomaniak, better than PHP mail()).
// Setup: copy contact-config.example.php to contact-config.php and fill in the mailbox password.
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

const LANGS = ['en', 'nl', 'pl'];

$lang = in_array($_POST['lang'] ?? '', LANGS, true) ? $_POST['lang'] : 'en';
function back(string $lang, string $status): never {
    header('Location: /' . $lang . '/contact.html?status=' . $status, true, 303);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { back($lang, 'error'); }

// Spam traps: honeypot filled, or submitted faster than 3 seconds after page load.
if (!empty($_POST['website'])) { back($lang, 'ok'); }
$ts = (int)($_POST['ts'] ?? 0);
if ($ts > 0 && (time() - $ts) < 3) { back($lang, 'ok'); }

$clean = static fn(string $v): string => trim(str_replace(["\r", "\n"], ' ', $v));
$name    = mb_substr($clean((string)($_POST['name'] ?? '')), 0, 100);
$email   = mb_substr($clean((string)($_POST['email'] ?? '')), 0, 150);
$message = mb_substr(trim((string)($_POST['message'] ?? '')), 0, 5000);

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($_POST['consent'])) {
    back($lang, 'error');
}

$cfgFile = __DIR__ . '/contact-config.php';
if (!is_file($cfgFile)) { error_log('contact-send: contact-config.php missing'); back($lang, 'error'); }
$cfg = require $cfgFile;

try {
    $m = new PHPMailer(true);
    $m->isSMTP();
    $m->Host       = $cfg['host'];
    $m->Port       = (int)$cfg['port'];
    $m->SMTPSecure = $cfg['secure'] === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
    $m->SMTPAuth   = true;
    $m->Username   = $cfg['username'];
    $m->Password   = $cfg['password'];
    $m->CharSet    = 'UTF-8';
    $m->setFrom($cfg['from'], $cfg['from_name']);
    $m->addAddress($cfg['to']);
    $m->addReplyTo($email, $name);
    $m->Subject = 'Website contact: ' . $name;
    $m->Body    = "Name: $name\nEmail: $email\nLanguage: $lang\n\n$message\n";
    $m->send();
    back($lang, 'ok');
} catch (Exception $e) {
    error_log('contact-send: ' . $e->getMessage());
    back($lang, 'error');
}
