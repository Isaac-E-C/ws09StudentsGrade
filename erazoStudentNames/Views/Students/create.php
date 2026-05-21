<section id="student-form-app" class="workspace">
    <div class="page-heading">
        <span>Student entry</span>
        <h1>Register grades</h1>
    </div>

    <form action="/" method="post" class="student-form">
        <?php if (($errors ?? []) !== []): ?>
            <div class="validation-summary">Please correct the highlighted fields.</div>
        <?php endif; ?>

        <div class="form-grid">
            <label>
                First name
                <input name="FirstName" value="<?= field_value('FirstName', $old ?? []) ?>" autocomplete="given-name" required>
                <span><?= e($errors['FirstName'] ?? '') ?></span>
            </label>

            <label>
                Last name
                <input name="LastName" value="<?= field_value('LastName', $old ?? []) ?>" autocomplete="family-name" required>
                <span><?= e($errors['LastName'] ?? '') ?></span>
            </label>

            <label>
                Email
                <input name="Email" value="<?= field_value('Email', $old ?? []) ?>" type="email" autocomplete="email" required>
                <span><?= e($errors['Email'] ?? '') ?></span>
            </label>

            <label>
                Phone number
                <input name="PhoneNumber" value="<?= field_value('PhoneNumber', $old ?? []) ?>" autocomplete="tel" required>
                <span><?= e($errors['PhoneNumber'] ?? '') ?></span>
            </label>

            <label>
                Postal code
                <input name="PostalCode" value="<?= field_value('PostalCode', $old ?? []) ?>" autocomplete="postal-code" required>
                <span><?= e($errors['PostalCode'] ?? '') ?></span>
            </label>

            <label>
                City
                <input name="City" value="<?= field_value('City', $old ?? []) ?>" autocomplete="address-level2" required>
                <span><?= e($errors['City'] ?? '') ?></span>
            </label>

            <label class="wide">
                Course
                <input name="CourseName" value="<?= field_value('CourseName', $old ?? []) ?>" required>
                <span><?= e($errors['CourseName'] ?? '') ?></span>
            </label>

            <label>
                Unit 1 grade
                <input name="UnitOneGrade" value="<?= field_value('UnitOneGrade', $old ?? []) ?>" type="number" min="0" max="20" step="0.01" v-model.number="unitOne" required>
                <span><?= e($errors['UnitOneGrade'] ?? '') ?></span>
            </label>

            <label>
                Unit 2 grade
                <input name="UnitTwoGrade" value="<?= field_value('UnitTwoGrade', $old ?? []) ?>" type="number" min="0" max="20" step="0.01" v-model.number="unitTwo" required>
                <span><?= e($errors['UnitTwoGrade'] ?? '') ?></span>
            </label>

            <label>
                Unit 3 grade
                <input name="UnitThreeGrade" value="<?= field_value('UnitThreeGrade', $old ?? []) ?>" type="number" min="0" max="20" step="0.01" v-model.number="unitThree" required>
                <span><?= e($errors['UnitThreeGrade'] ?? '') ?></span>
            </label>
        </div>

        <aside class="grade-preview" aria-live="polite">
            <div>
                <span>Student mean</span>
                <strong>{{ mean }}</strong>
            </div>
            <div>
                <span>Status</span>
                <strong :class="passed ? 'pass' : 'fail'">{{ passed ? 'Pass' : 'Not pass' }}</strong>
            </div>
        </aside>

        <button type="submit">Save student</button>
    </form>
</section>

<script>
    Vue.createApp({
        data() {
            return {
                unitOne: Number('<?= e($old['UnitOneGrade'] ?? 0) ?>'),
                unitTwo: Number('<?= e($old['UnitTwoGrade'] ?? 0) ?>'),
                unitThree: Number('<?= e($old['UnitThreeGrade'] ?? 0) ?>')
            };
        },
        computed: {
            mean() {
                return ((this.unitOne + this.unitTwo + this.unitThree) / 3).toFixed(2);
            },
            passed() {
                return Number(this.mean) >= 14;
            }
        }
    }).mount('#student-form-app');
</script>
