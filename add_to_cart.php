<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: logowanie.php");
    exit("Musisz być zalogowany, aby dodać produkt do koszyka.");
}

// Pobierz dane z formularza (użycie `id` zamiast `product_id`)
$productId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : 'add';

// Walidacja danych wejściowych
if ($productId <= 0) {
    header("Location: produkt.php?id=$productId");
    exit("Nieprawidłowe dane.");
}

$userId = $_SESSION['user']['id'];

// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Sprawdź, czy produkt już istnieje w koszyku
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Jeśli produkt już istnieje, zwiększ ilość o 1
    $row = $result->fetch_assoc();
    $newQuantity = $row['quantity'] + 1;
    $cartId = $row['id'];

    $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $updateStmt->bind_param("ii", $newQuantity, $cartId);
    $updateStmt->execute();
    $updateStmt->close();
} else {
    // Jeśli produkt nie istnieje, dodaj nowy wpis do koszyka
    $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insertStmt->bind_param("ii", $userId, $productId);
    $insertStmt->execute();
    $insertStmt->close();
}

$stmt->close();
$conn->close();

// Przekierowanie w zależności od akcji
if ($action === 'buy') {
    header("Location: koszyk.php");
    exit();
} else {
    header("Location: produkt.php?id=$productId");
    exit();
}
?>
