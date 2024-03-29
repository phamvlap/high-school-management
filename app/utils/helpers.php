<?php

function render_view(string $document, array $data = []): void
{
    $path = APP_DIR . "/views/{$document}.php";
    extract($data, EXTR_PREFIX_SAME, '__var__');
    require($path);
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit();
}

function html_escape(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
