<?php

namespace App\utils;

class Helper
{
    static public function redirectTo(string $url, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
        header('Location:' . $url);
        exit();
    }

    static public function renderPage(string $page, array $data = []): void
    {
        extract($data, EXTR_PREFIX_SAME, '__var__');
        require __DIR__ . '/../views' . $page;
    }

    static public function htmlEscape(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
