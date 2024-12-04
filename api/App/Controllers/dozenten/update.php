<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];
    $input = json_decode(file_get_contents('php://input'), true);

    $fieldsToUpdate = [];
    $params = [];

    // Check and sanitize each field
    if (isset($input['vorname'])) {
        $fieldsToUpdate[] = 'vorname = :vorname';
        $params[':vorname'] = htmlspecialchars($input['vorname'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['nachname'])) {
        $fieldsToUpdate[] = 'nachname = :nachname';
        $params[':nachname'] = htmlspecialchars($input['nachname'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['strasse'])) {
        $fieldsToUpdate[] = 'strasse = :strasse';
        $params[':strasse'] = htmlspecialchars($input['strasse'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['plz'])) {
        $fieldsToUpdate[] = 'plz = :plz';
        $params[':plz'] = htmlspecialchars($input['plz'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['ort'])) {
        $fieldsToUpdate[] = 'ort = :ort';
        $params[':ort'] = htmlspecialchars($input['ort'], ENT_QUOTES, 'UTF-8');
    }
    if (isset($input['fk_land'])) {
        $fieldsToUpdate[] = 'fk_land = :fk_land';
        $params[':fk_land'] = $input['fk_land'];
    }

    if (empty($fieldsToUpdate)) {
        Response::json([
            'status' => 'error',
            'message' => 'No fields to update.'
        ], Response::BAD_REQUEST);
        exit;
    }

    $params[':id'] = $id;

    try {
        $query = 'UPDATE tbl_dozenten SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_dozent = :id';
        $stmt = $db->query($query);
        $stmt->execute($params);

        Response::json([
            'status' => 'success',
            'message' => 'Dozent updated successfully.'
        ], Response::OK);
    } catch (Exception $e) {
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the dozent.'
        ], Response::SERVER_ERROR);
    }
} else {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter.'
    ], Response::BAD_REQUEST);
}
