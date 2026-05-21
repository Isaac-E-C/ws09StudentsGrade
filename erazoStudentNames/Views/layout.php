<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Student Grades') ?> - Student Grades</title>
    <link rel="stylesheet" href="/css/site.css">
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
</head>
<body>
    <header class="topbar">
        <?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'; ?>
        <a class="brand" href="/">
            <span class="brand-mark">SG</span>
            <span>Student Grades</span>
        </a>
        <nav class="nav-menu" aria-label="Main navigation">
            <a class="<?= in_array($currentPath, ['/', '/students/create'], true) ? 'active' : '' ?>" href="/">Student Form</a>
            <a class="<?= $currentPath === '/students' ? 'active' : '' ?>" href="/students">Class Table</a>
        </nav>
    </header>

    <main class="page-shell">
        <?= $content ?>
    </main>
</body>
</html>
