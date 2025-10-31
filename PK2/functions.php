<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function uid(): int
{
    return (int)($_SESSION['user']['id'] ?? 0);
}

function is_auth(): bool
{
    return uid() > 0;
}

function is_admin(): bool
{
    return !empty($_SESSION['user']['is_admin']);
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function post(string $key, $default = null)
{
    return $_POST[$key] ?? $default;
}

function getv(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}

function require_auth(): void
{
    if (!is_auth()) {
        $back = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        redirect('login.php?back=' . $back);
    }
}

function require_admin(): void
{
    if (!is_admin()) {
        http_response_code(403);
        echo 'Доступ запрещён';
        exit;
    }
}
