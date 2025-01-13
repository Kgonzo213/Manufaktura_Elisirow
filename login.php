<?php
session_start();

// Ustawienia połączenia z bazą danych
$servername = "localhost";
$username = "root"; // Zmień na swoją nazwę użytkownika MySQL, jeśli inna
$password = ""; // Zmień na swoje hasło MySQL, jeśli ustawione
$dbname = "manufaktura"; // Nazwa bazy danych

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa logowania
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Przygotowanie zapytania SQL
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $passwordHash);
    $stmt->fetch();

    // Weryfikacja hasła
    if ($passwordHash && password_verify($password, $passwordHash)) {
        $_SESSION['user_id'] = $id;
        header('Location: strona_glowna.html');
        exit();
    } else {
        echo "<script>alert('Nieprawidłowe dane logowania!'); window.location.href='login.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufaktura Eliksirów - Logowanie</title>
    <link rel="stylesheet" href="styl1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Logowanie</h1>
    </header>

    <div class="center-div">
        <form action="login.php" method="POST">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" required>

            <p><button type="submit" class="button">Zaloguj się</button></p>
            <a href="register.php">Rejestracja</a>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
