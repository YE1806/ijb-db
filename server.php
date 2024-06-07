<?php
// Überprüfe, ob eine GET-Anfrage gestellt wurde
$servername = "localhost";
$username = "root";
$password = "Yasemin2012";
$dbname = "kontakte";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfe, ob bestimmte GET-Parameter gesetzt sind
    if (isset($_POST['name']) && isset($_POST['surname'])) {
        $name = htmlspecialchars($_POST['name']); // Sicherheit: Eingaben escapen
        $surname = $_POST['surname'];
        $telefon = $_POST['telefon'];
        $seviye = $_POST['seviye'];
        $vazife = $_POST['vazife'];
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn -> connect_error) {
            die("Connection failed:");
        } else {
            // Erstelle eine Antwort
            $response = [
            'status' => 'success',
            'message' => "Eintrag war erfolgreich!"
        ];
        }


        $sql = "SELECT COUNT(*) FROM eintrag";
        $count_result = $conn->query($sql);
        $new_id = $count_result->fetch_array()[0] ?? '';
        $new_id += 1;
        $stmt = $conn->prepare("INSERT INTO eintrag (id,firstname,surname,telefon,niveau,vazife) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss", $new_id, $name, $surname, $telefon, $seviye, $vazife);
        $stmt->execute();
        $stmt->close();
        $conn->close();

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
