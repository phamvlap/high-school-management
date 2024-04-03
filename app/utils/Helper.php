<?php

namespace App\utils;

class Helper
{
    static public function redirectTo(string $url, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
        header('Location: ' . $url);
        exit();
    }

    static public function renderPage(string $page, array $data = []): void
    {
        extract($data, EXTR_PREFIX_SAME, '__var__');
        require __DIR__ . '/../views' . $page;
    }

    static public function htmlEscape(string|null $string): string
    {
        if (!$string) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    static public function getFromSession(string $key): string
    {
        $value = $_SESSION[$key] ?? '';
        return self::htmlEscape($value);
    }

    static public function getOnceFromSession(string $key): string
    {
        $value = $_SESSION[$key] ?? '';
        unset($_SESSION[$key]);
        return self::htmlEscape($value);
    }

    static public function removeFromSession(string $key): void
    {
        unset($_SESSION[$key]);
    }

    static public function getFormDataFromSession(string $key): string
    {
        $value = $_SESSION['form'][$key] ?? '';
        unset($_SESSION['form'][$key]);
        return self::htmlEscape($value);
    }

    static public function getFormErrorFromSession(string $key): string
    {
        $value = $_SESSION['errors'][$key] ?? '';
        unset($_SESSION['errors'][$key]);
        return self::htmlEscape($value);
    }

    static public function setIntoSession(string $key, string|array $value): void
    {
        $_SESSION[$key] = $value;
    }

    static public function getPrefixUrl(): string
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        return explode('/', $uri)[1];
    }

    // format date from yyyy-mm-dd to dd-mm-yyyy
    static public function formatDate(string $date): string
    {
        return date('d-m-Y', strtotime($date));
    }

    static public function isLogged(): bool
    {
        return isset($_SESSION['auth']);
    }

    static public function isPermitted(array $allowedTypes): bool
    {
        return in_array($_SESSION['auth']['type'], $allowedTypes);
    } 
}
