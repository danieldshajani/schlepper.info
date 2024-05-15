<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formulardaten erfassen und bereinigen
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Überprüfen, ob alle Felder ausgefüllt sind
    if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Fehlermeldung anzeigen, falls ein Feld nicht korrekt ausgefüllt ist
        echo "Bitte füllen Sie alle Felder korrekt aus.";
        exit;
    }

    // Daten vorbereiten
    $data = array(
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message
    );

    // cURL initialisieren
    $ch = curl_init('https://prod-125.westeurope.logic.azure.com:443/workflows/5b37210f27d94de78d8d48fd03bacd35/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=Zn2DKrqyQyQGUwfHoVvZFX2GjLTTtYqvMHob89IPDWI');
    
    // cURL Optionen setzen
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));

    // Ausführen und Antwort erhalten
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // cURL schließen
    curl_close($ch);

    // Erfolg oder Fehler anzeigen
    if ($httpcode == 200) {
        echo "Danke! Ihre Nachricht wurde gesendet.";
    } else {
        echo "Entschuldigung, es gab ein Problem beim Senden Ihrer Nachricht. Fehlercode: " . $httpcode;
    }
} else {
    // Fehlermeldung anzeigen, falls das Formular nicht per POST gesendet wurde
    echo "Es gab ein Problem mit Ihrer Übermittlung. Bitte versuchen Sie es erneut.";
}
?>
