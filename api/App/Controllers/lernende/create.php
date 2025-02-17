<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars
$vorname = htmlspecialchars($input['vorname'] ?? '', ENT_QUOTES, 'UTF-8');
$nachname = htmlspecialchars($input['nachname'] ?? '', ENT_QUOTES, 'UTF-8');
$strasse = htmlspecialchars($input['strasse'] ?? '', ENT_QUOTES, 'UTF-8');
$plz = htmlspecialchars($input['plz'] ?? '', ENT_QUOTES, 'UTF-8');
$ort = htmlspecialchars($input['ort'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_land = isset($input['fk_land']) ? (int)$input['fk_land'] : null;
$geschlecht = htmlspecialchars($input['geschlecht'] ?? '', ENT_QUOTES, 'UTF-8');
$telefon = htmlspecialchars($input['telefon'] ?? '', ENT_QUOTES, 'UTF-8');
$handy = htmlspecialchars($input['handy'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($input['email'] ?? '', ENT_QUOTES, 'UTF-8');
$email_privat = htmlspecialchars($input['email_privat'] ?? '', ENT_QUOTES, 'UTF-8');
$birthdate = htmlspecialchars($input['birthdate'] ?? '', ENT_QUOTES, 'UTF-8');

// Validation
$errors = [];
if (empty($vorname)) $errors['vorname'] = 'Vorname is required.';
if (empty($nachname)) $errors['nachname'] = 'Nachname is required.';
if (empty($strasse)) $errors['strasse'] = 'Strasse is required.';
if (empty($plz)) $errors['plz'] = 'PLZ is required.';
if (empty($ort)) $errors['ort'] = 'Ort is required.';
if (empty($fk_land)) $errors['fk_land'] = 'Land ID is required.';
if (empty($geschlecht)) $errors['geschlecht'] = 'Geschlecht is required.';
if (empty($telefon)) $errors['telefon'] = 'Telefon is required.';
if (empty($handy)) $errors['handy'] = 'Handy is required.';
if (empty($email)) $errors['email'] = 'Email is required.';
if (empty($email_privat)) $errors['email_privat'] = 'Private Email is required.';
if (empty($birthdate)) $errors['birthdate'] = 'Birthdate is required.';

if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Bad request',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Insert query
$query = 'INSERT INTO tbl_lernende (vorname, nachname, strasse, plz, ort, fk_land, geschlecht, telefon, handy, email, email_privat, birthdate) 
          VALUES (:vorname, :nachname, :strasse, :plz, :ort, :fk_land, :geschlecht, :telefon, :handy, :email, :email_privat, :birthdate)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':vorname' => $vorname,
        ':nachname' => $nachname,
        ':strasse' => $strasse,
        ':plz' => $plz,
        ':ort' => $ort,
        ':fk_land' => $fk_land,
        ':geschlecht' => $geschlecht,
        ':telefon' => $telefon,
        ':handy' => $handy,
        ':email' => $email,
        ':email_privat' => $email_privat,
        ':birthdate' => $birthdate
    ]);

    Response::json([
        'status' => 'success',
        'message' => 'Lernende created successfully!'
    ], Response::CREATED);

} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the Lernende',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
?>
