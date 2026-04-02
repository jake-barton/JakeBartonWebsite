<?php
/**
 * vcard.php — Serves a vCard 3.0 contact file for Jake Barton
 * Route: /vcard  →  downloads Jake-Barton.vcf
 * Apple Contacts, Google Contacts, and all standards-compliant
 * contact apps can import this directly.
 */

require_once __DIR__ . '/includes/content.php';

header('Content-Type: text/vcard; charset=utf-8');
header('Content-Disposition: attachment; filename="Jake-Barton.vcf"');
header('Cache-Control: no-store');

// vCard 3.0 — widest compatibility (iOS, macOS, Android, Outlook)
$phone_clean = preg_replace('/[^0-9+]/', '', $content['phone']);
$name_parts  = explode(' ', $content['name']);
$first = $name_parts[0] ?? '';
$last  = $name_parts[1] ?? '';

echo "BEGIN:VCARD\r\n";
echo "VERSION:3.0\r\n";
echo "N:{$last};{$first};;;\r\n";
echo "FN:{$content['name']}\r\n";
echo "TITLE:Gameplay Programmer & Technical Designer\r\n";
echo "ORG:{$content['university']}\r\n";
echo "EMAIL;TYPE=INTERNET,WORK:{$content['email']}\r\n";
echo "TEL;TYPE=CELL:{$phone_clean}\r\n";
echo "URL;TYPE=WORK:{$content['website']}\r\n";
echo "URL;TYPE=LinkedIn:https://www.linkedin.com/in/{$content['linkedin']}\r\n";
echo "URL;TYPE=GitHub:https://github.com/{$content['github']}\r\n";
echo "ADR;TYPE=WORK:;;{$content['location']};;;;\r\n";
echo "NOTE:Gameplay Programmer & Technical Designer. Lead Programmer at Samford Game Design Studio. Game Design & 3D Animation | CS Minor. Graduation: {$content['grad_year']}.\r\n";
echo "END:VCARD\r\n";
