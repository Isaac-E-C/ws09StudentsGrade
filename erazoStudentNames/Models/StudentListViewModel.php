<?php

declare(strict_types=1);

namespace App\Models;

final class StudentListViewModel
{
    /**
     * @param array<int, array<string, mixed>> $students
     */
    public function __construct(public readonly array $students)
    {
    }

    public function classMean(): float
    {
        if (count($this->students) === 0) {
            return 0.0;
        }

        $total = array_sum(array_map('grade_mean', $this->students));

        return round($total / count($this->students), 2);
    }
}
