<?php

declare(strict_types=1);

use App\Student;
use App\StudentService;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/helpers.php';

session_start();

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);

    ob_start();
    require dirname(__DIR__) . "/Views/{$view}.php";
    $content = ob_get_clean();

    require dirname(__DIR__) . '/Views/layout.php';
}

function redirect_to(string $path): never
{
    header("Location: {$path}");
    exit;
}

function validate_student(array $data): array
{
    $errors = [];
    $required = [
        'FirstName' => 'First name is required.',
        'LastName' => 'Last name is required.',
        'Email' => 'Email is required.',
        'PhoneNumber' => 'Phone number is required.',
        'PostalCode' => 'Postal code is required.',
        'City' => 'City is required.',
        'CourseName' => 'Course is required.',
    ];

    foreach ($required as $field => $message) {
        if (trim((string) ($data[$field] ?? '')) === '') {
            $errors[$field] = $message;
        }
    }

    if (!isset($errors['Email']) && !filter_var($data['Email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $errors['Email'] = 'Email is not valid.';
    }

    foreach (['UnitOneGrade', 'UnitTwoGrade', 'UnitThreeGrade'] as $field) {
        $value = $data[$field] ?? null;
        if ($value === null || $value === '' || !is_numeric($value)) {
            $errors[$field] = 'Grade is required.';
            continue;
        }

        $grade = (float) $value;
        if ($grade < 0 || $grade > 20) {
            $errors[$field] = 'Grade must be between 0 and 20.';
        }
    }

    return $errors;
}

try {
    $service = new StudentService();

    if ($method === 'GET' && in_array($path, ['/', '/students/create'], true)) {
        render('students/create', [
            'title' => 'Student Form',
            'old' => [],
            'errors' => [],
        ]);
        exit;
    }

    if ($method === 'POST' && in_array($path, ['/', '/students/create'], true)) {
        $old = $_POST;
        $errors = validate_student($old);

        if ($errors !== []) {
            render('students/create', [
                'title' => 'Student Form',
                'old' => $old,
                'errors' => $errors,
            ]);
            exit;
        }

        $service->create(Student::fromArray($old));
        $_SESSION['success'] = 'Student saved successfully.';
        redirect_to('/students');
    }

    if ($method === 'GET' && $path === '/students') {
        $students = $service->getAll();
        $classMean = 0.0;

        if (count($students) > 0) {
            $total = array_sum(array_map('grade_mean', $students));
            $classMean = round($total / count($students), 2);
        }

        $successMessage = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        render('students/index', [
            'title' => 'Class Table',
            'students' => $students,
            'classMean' => $classMean,
            'successMessage' => $successMessage,
        ]);
        exit;
    }

    http_response_code(404);
    render('students/error', ['title' => 'Not Found']);
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    render('students/error', ['title' => 'Error']);
}
