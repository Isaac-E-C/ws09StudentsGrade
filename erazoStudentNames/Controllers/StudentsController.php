<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Student;
use App\Models\StudentListViewModel;
use App\Services\StudentService;

final class StudentsController
{
    public function __construct(private readonly StudentService $studentService)
    {
    }

    public function create(): void
    {
        $this->render('students/create', [
            'title' => 'Student Form',
            'old' => [],
            'errors' => [],
        ]);
    }

    public function store(array $postData): void
    {
        $errors = $this->validateStudent($postData);

        if ($errors !== []) {
            $this->render('students/create', [
                'title' => 'Student Form',
                'old' => $postData,
                'errors' => $errors,
            ]);
            return;
        }

        $this->studentService->create(Student::fromArray($postData));
        $_SESSION['success'] = 'Student saved successfully.';
        $this->redirect('/students');
    }

    public function index(): void
    {
        $viewModel = new StudentListViewModel($this->studentService->getAll());
        $successMessage = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);

        $this->render('students/index', [
            'title' => 'Class Table',
            'students' => $viewModel->students,
            'classMean' => $viewModel->classMean(),
            'successMessage' => $successMessage,
        ]);
    }

    public function error(int $statusCode = 500, string $title = 'Error'): void
    {
        http_response_code($statusCode);
        $this->render('students/error', ['title' => $title]);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require app_view_path($view);
        $content = ob_get_clean();

        require dirname(__DIR__) . '/Views/layout.php';
    }

    private function redirect(string $path): never
    {
        header("Location: {$path}");
        exit;
    }

    /**
     * @return array<string, string>
     */
    private function validateStudent(array $data): array
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
}
