<?php

declare(strict_types=1);

use App\Controllers\StudentsController;
use App\Services\StudentService;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/helpers.php';

session_start();

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new StudentsController(new StudentService());

    if ($method === 'GET' && in_array($path, ['/', '/students/create'], true)) {
        $controller->create();
        exit;
    }

    if ($method === 'POST' && in_array($path, ['/', '/students/create'], true)) {
        $controller->store($_POST);
        exit;
    }

    if ($method === 'GET' && $path === '/students') {
        $controller->index();
        exit;
    }

    $controller->error(404, 'Not Found');
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(500);

    extract(['title' => 'Error'], EXTR_SKIP);
    ob_start();
    require dirname(__DIR__) . '/Views/students/error.php';
    $content = ob_get_clean();
    require dirname(__DIR__) . '/Views/layout.php';
}
