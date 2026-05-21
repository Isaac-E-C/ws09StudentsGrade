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
