<?php
session_start();

// Ustawienia połączenia z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

// Połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa rejestracji
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];
    $street = htmlspecialchars($_POST['street']);
    $houseNumber = htmlspecialchars($_POST['house-number']);
    $postalCode = htmlspecialchars($_POST['postal-code']);
    $city = htmlspecialchars($_POST['city']);

    // Sprawdzenie zgodności haseł
    if ($password !== $passwordConfirm) {
        echo "<script>alert('Hasła nie pasują!'); window.location.href='register.php';</script>";
        exit();
    }

    // Hashowanie hasła
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Obsługa zdjęcia profilowego
    if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";

        // Sprawdź, czy katalog docelowy istnieje, jeśli nie – utwórz go
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Walidacja typu pliku
        $fileTmpPath = $_FILES['profile-picture']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>alert('Niedozwolony typ pliku!'); window.location.href='register.php';</script>";
            exit();
        }

        // Generowanie unikalnej nazwy pliku
        $fileName = uniqid('', true) . '_' . basename($_FILES['profile-picture']['name']);
        $profilePicturePath = $uploadDir . $fileName;

        // Przenoszenie pliku do katalogu docelowego
        if (!move_uploaded_file($fileTmpPath, $profilePicturePath)) {
            echo "<script>alert('Nie udało się przesłać pliku!'); window.location.href='register.php';</script>";
            exit();
        }

        // Ścieżka zapisywana w bazie danych (może być względna)
        // Tutaj akurat zostanie "uploads/nazwa_pliku"
    } else {
        // Jeżeli nie wybrano pliku lub jest błąd – można ustawić domyślne zdjęcie lub null
        $profilePicturePath = null;
    }

    // Wstawianie danych do bazy
    $stmt = $conn->prepare(
        "INSERT INTO users (username, password_hash, street, house_number, postal_code, city, profile_picture) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    $stmt->bind_param("sssssss", 
        $username,
        $passwordHash,
        $street,
        $houseNumber,
        $postalCode,
        $city,
        $profilePicturePath
    );

    if ($stmt->execute()) {
        // Zapisz dane w sesji, aby wyświetlić na stronie sukcesu
        $_SESSION['registration_data'] = [
            'username'        => $username,
            'street'          => $street,
            'house_number'    => $houseNumber,
            'postal_code'     => $postalCode,
            'city'            => $city,
            'profile_picture' => $profilePicturePath
        ];

        // Przekierowanie na stronę z komunikatem o sukcesie
        header("Location: registration_success.php");
        exit();
    } else {
        echo "<script>alert('Błąd zapisu w bazie danych: " . $stmt->error . "'); window.location.href='register.php';</script>";
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
    <title>Manufaktura Eliksirów - Rejestracja</title>
    <link rel="stylesheet" href="styl1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="main.js" defer></script>
</head>
<body>
    
<?php include 'nav.php'; ?>

    <header>
        <h1>Rejestracja</h1>
    </header>

    <div class="center-div">
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <label for="reg-username">Nazwa użytkownika:</label>
            <input type="text" id="reg-username" name="username" placeholder="Wprowadź nazwę użytkownika" required>

            <label for="reg-password">Hasło:</label>
            <input type="password" id="reg-password" name="password" placeholder="Wprowadź hasło" required>

            <label for="reg-password-confirm">Potwierdź hasło:</label>
            <input type="password" id="reg-password-confirm" name="password-confirm" placeholder="Powtórz hasło" required>

            <label for="reg-street">Ulica:</label>
            <input type="text" id="reg-street" name="street" placeholder="Wprowadź ulicę">

            <label for="reg-house-number">Numer domu/mieszkania:</label>
            <input type="text" id="reg-house-number" name="house-number" placeholder="Wprowadź numer domu/mieszkania">

            <label for="reg-postal-code">Kod pocztowy:</label>
            <input type="text" id="reg-postal-code" name="postal-code" placeholder="XX-XXX" 
                   pattern="\d{2}-\d{3}" 
                   title="Kod pocztowy powinien mieć format XX-XXX">

            <label for="reg-city">Miasto:</label>
            <input type="text" id="reg-city" name="city" placeholder="Wprowadź miasto">

            <label for="profile-picture">Zdjęcie profilowe:</label>
            <input type="file" id="profile-picture" name="profile-picture" accept="image/*">

            <p><button type="submit" class="button">Zarejestruj się</button></p>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
