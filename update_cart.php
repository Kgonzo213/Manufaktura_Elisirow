<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    header("Location: logowanie.php");
    exit();
}

$userId = $_SESSION['user']['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sprawdzenie, jaka akcja została wykonana
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Usuwanie produktu z koszyka
        if (strpos($action, 'delete_') === 0) {
            $cartId = (int)str_replace('delete_', '', $action);
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $cartId, $userId);
            $stmt->execute();
            $stmt->close();

            header("Location: koszyk.php");
            exit();
        }

        // Aktualizacja ilości produktów
        if ($action === 'update' && isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $cartId => $newQuantity) {
                $cartId = (int)$cartId;
                $newQuantity = (int)$newQuantity;

                if ($newQuantity > 0) {
                    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
                    $stmt->bind_param("iii", $newQuantity, $cartId, $userId);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            header("Location: koszyk.php");
            exit();
        }
    }
}

$conn->close();
header("Location: koszyk.php");
exit();
?>
