<?php
$servername = "localhost";
$username = "root"; // Zmień na swoją nazwę użytkownika MySQL, jeśli inna
$password = ""; // Zmień na swoje hasło MySQL, jeśli ustawione
$dbname = "manufaktura"; // Nazwa bazy danych powinna odpowiadać Twojej bazie

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
