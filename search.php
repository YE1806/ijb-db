<?php
// Überprüfe, ob eine GET-Anfrage gestellt wurde
$servername = "localhost";
$username = "root";
$password = "Yasemin2012";
$dbname = "kontakte";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfe, ob bestimmte GET-Parameter gesetzt sind
    if (isset($_POST['name2']) || isset($_POST['surname2']) || isset($_POST['seviye2']) || isset($_POST['vazife2'])) {
        $name = htmlspecialchars($_POST['name2']); // Sicherheit: Eingaben escapen
        $surname = htmlspecialchars($_POST['surname2']);
        $telefon = htmlspecialchars($_POST['telefon2']);
        $seviye = htmlspecialchars($_POST['seviye2']);
        $vazife = htmlspecialchars($_POST['vazife2']);
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn -> connect_error) {
            die("Connection failed:");
        }

        if(!empty($_POST['name2'])) {
            $whereClauses[] = "firstname = ?";
            $params[] = $_POST['name2'];
            $types .= 's';
        }

        if(!empty($_POST['surname2'])) {
            $whereClauses[] = "surname = ?";
            $params[] = $_POST['surname2'];
            $types .= 's';
        }

        if(!empty($_POST['seviye2'])) {
            $whereClauses[] = "niveau = ?";
            $params[] = $_POST['seviye2'];
            $types .= 's';
        }

        if(!empty($_POST['vazife2'])) {
            $whereClauses[] = "vazife = ?";
            $params[] = $_POST['name2'];
            $types .= 's';
        }

        $sql = "SELECT * FROM eintrag";

        if (!empty($whereClauses)) {
            $sql .= 'WHERE  ' . implode(' AND ', $whereClauses);
        }

        $stmt = $conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        echo($result);
        $stmt->close();
        $conn->close();

        // Erstelle eine Antwort
        $response = [
            'status' => 'success',
            'message' => "Abfrage erfolgreich!"
        ];

    } else {
        // Fehlende Parameter
        $response = [
            'status' => 'error',
            'message' => 'Fehlende Parameter. Bitte name und nachname angeben.'
        ];
    }
    
    // Setze den Content-Type Header auf JSON

    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    // Gebe die Antwort als JSON zurück
    echo json_encode($response);
} else {
    // Ungültige Anfrage-Methode
    $response = [
        'status' => 'error',
        'message' => 'Ungültige Anfrage-Methode. Bitte eine GET-Anfrage stellen.'
    ];
    
    // Setze den Content-Type Header auf JSON
    header('Content-Type: application/json');

    
    // Gebe die Antwort als JSON zurück
    echo json_encode($response);
}


?>
