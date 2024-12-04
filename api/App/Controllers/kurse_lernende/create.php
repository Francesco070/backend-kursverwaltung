<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs to prevent SQL injection
$fk_lernende = (int)($input['fk_lernende'] ?? 0);
$fk_kurs = (int)($input['fk_kurs'] ?? 0);
$role = htmlspecialchars($input['role'] ?? '', ENT_QUOTES, 'UTF-8');

// Validate inputs
$errors = [];
if ($fk_lernende === 0) $errors['fk_lernende'] = 'Lernende ID is required.';
if ($fk_kurs === 0) $errors['fk_kurs'] = 'Kurs ID is required.';
if (empty($role)) $errors['role'] = 'Role is required.';

if (!empty($errors)) {
    // Send error response in JSON format with a 400 Bad Request status
    Response::json([
        'status' => 'error',
        'message' => 'Bad request',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Insert data into the database
$query = 'INSERT INTO tbl_kurse_lernende (fk_lernende, fk_kurs, role) VALUES (:fk_lernende, :fk_kurs, :role)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lernende' => $fk_lernende,
        ':fk_kurs' => $fk_kurs,
        ':role' => $role
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Kurs-Lernende relationship created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the kurs_lernende relationship',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
