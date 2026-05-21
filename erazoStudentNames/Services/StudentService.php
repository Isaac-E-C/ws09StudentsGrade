<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Student;
use MongoDB\Client;
use MongoDB\Collection;

final class StudentService
{
    private Collection $students;

    public function __construct()
    {
        $mongoUri = getenv('MONGODB_URI') ?: null;

        if ($mongoUri === null || trim($mongoUri) === '') {
            throw new \RuntimeException('MONGODB_URI is required.');
        }

        $databaseName = getenv('MONGODB_DATABASE') ?: 'student_grades';
        $client = new Client($mongoUri);
        $this->students = $client
            ->selectDatabase($databaseName)
            ->selectCollection('students');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->students
            ->find([], ['sort' => ['CreatedAt' => -1]])
            ->toArray();
    }

    public function create(Student $student): void
    {
        $this->students->insertOne($student->toDocument());
    }
}
