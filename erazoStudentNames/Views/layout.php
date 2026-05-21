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
        <a class="brand" href="/">Student Grades</a>
        <nav class="nav-menu" aria-label="Main navigation">
            <a href="/">Student Form</a>
            <a href="/students">Class Table</a>
        </nav>
    </header>

    <main class="page-shell">
        <?= $content ?>
    </main>
</body>
</html>
