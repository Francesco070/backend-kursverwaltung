<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to select all columns from tbl_lehrbetrieb and only id_lernende, vorname, nachname from tbl_lernende
    $query = 'SELECT 
                lb.id_lehrbetrieb, lb.firma, lb.strasse, lb.plz, lb.ort,
                l.id_lernende, l.vorname, l.nachname
            FROM tbl_lehrbetrieb AS lb
            LEFT JOIN tbl_lehrbetrieb_lernende AS lbl ON lb.id_lehrbetrieb = lbl.fk_lehrbetrieb
            LEFT JOIN tbl_lernende AS l ON lbl.fk_lernende = l.id_lernende';

    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $results = $stmt->fetchAll();

    // Organize data into a structured array
    $lehrbetriebe = [];
    foreach ($results as $row) {
        $id_lehrbetrieb = $row['id_lehrbetrieb'];

        if (!isset($lehrbetriebe[$id_lehrbetrieb])) {
            $lehrbetriebe[$id_lehrbetrieb] = [
                'id_lehrbetrieb' => $row['id_lehrbetrieb'],
                'firma' => $row['firma'],
                'strasse' => $row['strasse'],
                'plz' => $row['plz'],
                'ort' => $row['ort'],
                'lernende' => []
            ];
        }

        if ($row['id_lernende']) {
            $lehrbetriebe[$id_lehrbetrieb]['lernende'][] = [
                'id_lernende' => $row['id_lernende'],
                'vorname' => $row['vorname'],
                'nachname' => $row['nachname']
            ];
        }
    }

    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetriebe retrieved successfully',
        'data' => array_values($lehrbetriebe)
    ], Response::OK);
} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving the Lehrbetriebe',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}