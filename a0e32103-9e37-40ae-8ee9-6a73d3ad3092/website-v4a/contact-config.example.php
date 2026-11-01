<?php
// Copy this file to contact-config.php (same folder) and fill in the real password.
// contact-config.php is blocked from web access by .htaccess. Never commit/share the real file.
return [
    'host'     => 'mail.infomaniak.com',
    'port'     => 465,                          // 465 = SSL (use 587 + 'secure' => 'tls' for STARTTLS)
    'secure'   => 'ssl',
    'username' => 'letsconnect@kasiamaciejowska.eu',   // the Infomaniak mailbox that sends the message
    'password' => 'CHANGE-ME',
    'from'     => 'letsconnect@kasiamaciejowska.eu',   // must be the authenticated mailbox
    'from_name'=> 'Website contact form',
    'to'       => 'letsconnect@kasiamaciejowska.eu',
];
