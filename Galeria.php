<?php
// galeria.php

// 1. Połączenie z bazą
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą: " . $conn->connect_error);
}

// 2. Pobierz listę produktów
$sql = "SELECT id, name, short_description, image_path FROM products";
$result = $conn->query($sql);

// Zamknij połączenie
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mity i Magia Eliksirów - Galeria</title>
    <link rel="stylesheet" href="styl1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="main.js"></script>
</head>
<body>
    
<?php include 'nav.php'; ?>

    <section>
        <h2>Nasze Eliksiry</h2>
        <div class="gallery">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div>
                        <!-- Nazwa produktu -->
                        <p><?php echo htmlspecialchars($row['name']); ?></p>
                        <!-- Link do produktu -->
                        <a href="produkt.php?id=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['name']); ?>">
                        </a>
                        <!-- Krótki opis (opcjonalnie) -->
                        <p style="font-size: 0.9em;">
                            <?php echo htmlspecialchars($row['short_description']); ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Brak produktów w galerii.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
