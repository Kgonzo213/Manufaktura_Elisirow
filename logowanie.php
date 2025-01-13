<?php
session_start();

//Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Wyszukiwanie użytkownika w bazie danych
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Weryfikacja hasła
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'profile_picture' => $user['profile_picture'],
                'street' => $user['street'],
                'house_number' => $user['house_number'],
                'postal_code' => $user['postal_code'],
                'city' => $user['city']
            ];

            // Przekierowanie na stronę główną
            header("Location: strona_glowna.php");
            exit();
        } else {
            echo "<script>alert('Nieprawidłowe hasło!'); window.location.href='logowanie.php';</script>";
        }
    } else {
        echo "<script>alert('Nie znaleziono użytkownika!'); window.location.href='logowanie.php';</script>";
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
    <title>Logowanie</title>
    <link rel="stylesheet" href="styl1.css">
</head>
<body>
    
<?php include 'nav.php'; ?>
    <header>
        <h1>Logowanie</h1>
    </header>

    <div class="center-div" >
        <form action="logowanie.php" method="POST">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" placeholder="Wprowadź nazwę użytkownika" required>
            
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" placeholder="Wprowadź hasło" required>
            
            <p><button type="submit" class="button">Zaloguj się</button></p>
            
            <a href="register.php">Rejestracja</a>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
