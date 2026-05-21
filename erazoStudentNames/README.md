# Student Grades PHP

PHP app for registering students and three unit grades with MongoDB Atlas storage.

## Features

- PHP folder structure with `public`, `src`, and `views`.
- Server-side form post and table rendering, without API endpoints.
- Vue 3 is used inside the form page for the live student mean and pass/fail preview.
- MongoDB Atlas connection through `MONGODB_URI`.
- Render deployment through Docker.

## Environment Variables

- `MONGODB_URI`: MongoDB Atlas connection string.
- `MONGODB_DATABASE`: Optional database name. Defaults to `student_grades`.

## Local Run

This machine needs PHP 8.2+, Composer, and the MongoDB PHP extension installed to run locally:

```powershell
composer install
$env:MONGODB_URI="your MongoDB Atlas connection string"
php -S localhost:8080 -t public public/index.php
```

Then open `http://localhost:8080`.
