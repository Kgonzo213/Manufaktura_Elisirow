<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: logowanie.php");
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mój Profil</title>
    <link rel="stylesheet" href="styl1.css">
</head>
<body>
    
<?php include 'nav.php'; ?>

    <div class="profile">
        <h1>Mój Profil</h1>
        <h1>
        <?php if ($user['profile_picture']): ?>
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil" style="max-width: 200px; border-radius: 50%;">
        <?php endif; ?></h1>
        <div class="center-div">
           
        <p><strong>Nazwa użytkownika:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Ulica:</strong> <?php echo htmlspecialchars($user['street']); ?></p>
        <p><strong>Numer domu/mieszkania:</strong> <?php echo htmlspecialchars($user['house_number']); ?></p>
        <p><strong>Kod pocztowy:</strong> <?php echo htmlspecialchars($user['postal_code']); ?></p>
        <p><strong>Miasto:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
