<?php
session_start();
if (!isset($_SESSION['registration_data'])) {
    header("Location: register.php");
    exit();
}

$registrationData = $_SESSION['registration_data'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja zakończona</title>
    <link rel="stylesheet" href="styl1.css">
</head>
<body>
    <header>
        <h1>Rejestracja zakończona</h1>
    </header>

    <div class="center-div">
        <p><strong>Nazwa użytkownika:</strong> <?php echo htmlspecialchars($registrationData['username']); ?></p>
        <p><strong>Ulica:</strong> <?php echo htmlspecialchars($registrationData['street']); ?></p>
        <p><strong>Numer domu/mieszkania:</strong> <?php echo htmlspecialchars($registrationData['house_number']); ?></p>
        <p><strong>Kod pocztowy:</strong> <?php echo htmlspecialchars($registrationData['postal_code']); ?></p>
        <p><strong>Miasto:</strong> <?php echo htmlspecialchars($registrationData['city']); ?></p>
        <?php if (!empty($registrationData['profile_picture'])): ?>
            <p><strong>Zdjęcie profilowe:</strong></p>
            <img src="<?php echo htmlspecialchars($registrationData['profile_picture']); ?>" alt="Zdjęcie profilowe" style="max-width: 200px; height: auto;">
        <?php else: ?>
            <p><strong>Zdjęcie profilowe:</strong> Brak przesłanego zdjęcia.</p>
        <?php endif; ?>
        <p>
            <a href="logowanie.html" class="button">Kontynuuj</a>
        </p>
    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
