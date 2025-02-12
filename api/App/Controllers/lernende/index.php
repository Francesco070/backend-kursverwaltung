<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// SQL Query to get all Lernende with Lehrbetriebe
$query = 'SELECT 
            l.id_lernende, l.vorname, l.nachname, l.strasse, l.plz, l.ort, 
            l.geschlecht, l.telefon, l.handy, l.email, l.email_privat, 
            l.birthdate, c.country,
            lb.id_lehrbetrieb, lb.firma, lb.strasse AS lb_strasse, lb.plz AS lb_plz, lb.ort AS lb_ort
          FROM tbl_lernende l
          JOIN tbl_countries c ON l.fk_land = c.id_countries
          LEFT JOIN tbl_lehrbetrieb_lernende lbl ON l.id_lernende = lbl.fk_lernende
          LEFT JOIN tbl_lehrbetrieb lb ON lbl.fk_lehrbetrieb = lb.id_lehrbetrieb';

try {
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Strukturieren der Daten: Gruppiere Lehrbetriebe pro Lernenden
    $data = [];
    foreach ($results as $row) {
        $id_lernende = $row['id_lernende'];

        if (!isset($data[$id_lernende])) {
            $data[$id_lernende] = [
                'id_lernende' => $row['id_lernende'],
                'vorname' => $row['vorname'],
                'nachname' => $row['nachname'],
                'strasse' => $row['strasse'],
                'plz' => $row['plz'],
                'ort' => $row['ort'],
                'geschlecht' => $row['geschlecht'],
                'telefon' => $row['telefon'],
                'handy' => $row['handy'],
                'email' => $row['email'],
                'email_privat' => $row['email_privat'],
                'birthdate' => $row['birthdate'],
                'country' => $row['country'],
                'lehrbetriebe' => []
            ];
        }

        // Falls ein Lehrbetrieb vorhanden ist, hinzufügen
        if ($row['id_lehrbetrieb']) {
            $data[$id_lernende]['lehrbetriebe'][] = [
                'id_lehrbetrieb' => $row['id_lehrbetrieb'],
                'firma' => $row['firma'],
                'strasse' => $row['lb_strasse'],
                'plz' => $row['lb_plz'],
                'ort' => $row['lb_ort']
            ];
        }
    }

    // Umwandlung in numerisches Array
    $data = array_values($data);

    Response::json([
        'status' => 'success',
        'data' => $data
    ], Response::OK);

} catch (Exception $e) {
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while fetching the Lernende records',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}
?>
