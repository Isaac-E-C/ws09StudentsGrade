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

function grade_mean(array|object $student): float
{
    $unitOne = (float) ($student['UnitOneGrade'] ?? 0);
    $unitTwo = (float) ($student['UnitTwoGrade'] ?? 0);
    $unitThree = (float) ($student['UnitThreeGrade'] ?? 0);

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
