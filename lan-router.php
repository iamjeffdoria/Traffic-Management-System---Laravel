<?php

// Router for the LAN server started by NativeAppServiceProvider.
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . '/public' . $uri;

// Serve real files (css, js, images) directly.
if ($uri !== '/' && is_file($file)) {
    return false;
}

require_once __DIR__ . '/public/index.php';