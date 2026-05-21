<section class="workspace">
    <div class="page-heading">
        <span>Class results</span>
        <h1>Students table</h1>
    </div>

    <?php if (!empty($successMessage)): ?>
        <div class="notice"><?= e($successMessage) ?></div>
    <?php endif; ?>

    <?php if (count($students ?? []) === 0): ?>
        <div class="empty-state">No students have been registered yet.</div>
    <?php else: ?>
        <div class="table-shell">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>Postal code</th>
                        <th>Course</th>
                        <th>Unit 1</th>
                        <th>Unit 2</th>
                        <th>Unit 3</th>
                        <th>Mean</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <?php $passed = grade_passed($student); ?>
                        <tr>
                            <td><?= e($student['FirstName'] ?? '') ?> <?= e($student['LastName'] ?? '') ?></td>
                            <td><?= e($student['Email'] ?? '') ?></td>
                            <td><?= e($student['City'] ?? '') ?></td>
                            <td><?= e($student['PostalCode'] ?? '') ?></td>
                            <td><?= e($student['CourseName'] ?? '') ?></td>
                            <td><?= e(number_format((float) ($student['UnitOneGrade'] ?? 0), 2, '.', '')) ?></td>
                            <td><?= e(number_format((float) ($student['UnitTwoGrade'] ?? 0), 2, '.', '')) ?></td>
                            <td><?= e(number_format((float) ($student['UnitThreeGrade'] ?? 0), 2, '.', '')) ?></td>
                            <td><?= e(number_format(grade_mean($student), 2, '.', '')) ?></td>
                            <td>
                                <span class="status-pill <?= $passed ? 'pass' : 'fail' ?>">
                                    <?= $passed ? 'Pass' : 'Not pass' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">Class mean</td>
                        <td colspan="2"><?= e(number_format((float) $classMean, 2, '.', '')) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</section>
