<?php
header('Content-Type: text/plain; charset=utf-8');

echo "Easy. sklad ping\n";
echo "PHP: " . PHP_VERSION . "\n";
echo "HTTPS: " . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'on' : 'off') . "\n";
echo "X-Forwarded-Proto: " . (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : '-') . "\n";
?>
