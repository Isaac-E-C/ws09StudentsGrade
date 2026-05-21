<?php

declare(strict_types=1);

namespace App\Models;

final class Student
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $postalCode,
        public readonly string $city,
        public readonly string $phoneNumber,
        public readonly string $courseName,
        public readonly float $unitOneGrade,
        public readonly float $unitTwoGrade,
        public readonly float $unitThreeGrade,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            trim((string) ($data['FirstName'] ?? '')),
            trim((string) ($data['LastName'] ?? '')),
            trim((string) ($data['Email'] ?? '')),
            trim((string) ($data['PostalCode'] ?? '')),
            trim((string) ($data['City'] ?? '')),
            trim((string) ($data['PhoneNumber'] ?? '')),
            trim((string) ($data['CourseName'] ?? '')),
            (float) ($data['UnitOneGrade'] ?? 0),
            (float) ($data['UnitTwoGrade'] ?? 0),
            (float) ($data['UnitThreeGrade'] ?? 0),
        );
    }

    public function mean(): float
    {
        return round(($this->unitOneGrade + $this->unitTwoGrade + $this->unitThreeGrade) / 3, 2);
    }

    public function passed(): bool
    {
        return $this->mean() >= 14;
    }

    public function toDocument(): array
    {
        return [
            'FirstName' => $this->firstName,
            'LastName' => $this->lastName,
            'Email' => $this->email,
            'PostalCode' => $this->postalCode,
            'City' => $this->city,
            'PhoneNumber' => $this->phoneNumber,
            'CourseName' => $this->courseName,
            'UnitOneGrade' => $this->unitOneGrade,
            'UnitTwoGrade' => $this->unitTwoGrade,
            'UnitThreeGrade' => $this->unitThreeGrade,
            'CreatedAt' => new \MongoDB\BSON\UTCDateTime(),
        ];
    }
}
