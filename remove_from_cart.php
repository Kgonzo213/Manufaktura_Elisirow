<?php
session_start();
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit("Musisz być zalogowany, aby usunąć produkt z koszyka.");
}

$cartId = (int)$_POST['cart_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
$stmt->bind_param("i", $cartId);

if ($stmt->execute()) {
    echo "Produkt usunięty z koszyka.";
} else {
    echo "Błąd podczas usuwania produktu.";
}

$stmt->close();
$conn->close();
?>
