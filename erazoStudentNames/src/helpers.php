<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function field_value(string $key, array $old): string
{
    return e($old[$key] ?? '');
}

function student_field(array|object $student, string $key, mixed $default = null): mixed
{
    if (is_array($student) || $student instanceof ArrayAccess) {
        return $student[$key] ?? $default;
    }

    return $student->{$key} ?? $default;
}

function numeric_value(mixed $value): float
{
    if ($value instanceof MongoDB\BSON\Decimal128) {
        return (float) (string) $value;
    }

    if ($value instanceof Stringable) {
        $value = (string) $value;
    }

    if (is_int($value) || is_float($value) || is_numeric($value)) {
        return (float) $value;
    }

    return 0.0;
}

function grade_value(array|object $student, string $key): float
{
    return numeric_value(student_field($student, $key, 0));
}

function grade_mean(array|object $student): float
{
    $unitOne = grade_value($student, 'UnitOneGrade');
    $unitTwo = grade_value($student, 'UnitTwoGrade');
    $unitThree = grade_value($student, 'UnitThreeGrade');

    return round(($unitOne + $unitTwo + $unitThree) / 3, 2);
}

function grade_passed(array|object $student): bool
{
    return grade_mean($student) >= 14;
}

function app_view_path(string $view): string
{
    $basePath = dirname(__DIR__) . '/Views';
    $candidates = [
        "{$basePath}/{$view}.php",
    ];

    if (str_contains($view, '/')) {
        [$folder, $file] = explode('/', $view, 2);
        $candidates[] = "{$basePath}/" . ucfirst($folder) . "/{$file}.php";
        $candidates[] = "{$basePath}/" . strtolower($folder) . "/{$file}.php";
    }

    foreach (array_unique($candidates) as $candidate) {
        if (is_file($candidate)) {
            return $candidate;
        }
    }

    throw new RuntimeException("View not found: {$view}");
}
