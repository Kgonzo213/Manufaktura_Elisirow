<?php
// produkt.php
// 1. Połączenie z bazą
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "manufaktura";

// Stwórz obiekt mysqli
$conn = new mysqli($servername, $username, $password, $dbname);
// Sprawdź połączenie
if ($conn->connect_error) {
    die("Błąd połączenia z bazą: " . $conn->connect_error);
}

// 2. Sprawdzenie, czy jest podany parametr id w GET
if (!isset($_GET['id'])) {
    die("Niepoprawne wywołanie strony. Brak parametru ID produktu.");
}

// Bezpieczne rzutowanie na int (chcemy mieć pewność, że to liczba)
$productId = (int) $_GET['id'];

// 3. Pobierz z bazy dane produktu
$sql = "SELECT * FROM products WHERE id = $productId LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    die("Nie znaleziono produktu o podanym ID.");
}

// 4. Wyciągnij dane do tablicy asocjacyjnej
$product = $result->fetch_assoc();

// 5. Zamknij połączenie
$conn->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Manufaktura Eliksirów</title>
    <link rel="stylesheet" href="styl1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="main.js"></script>
    <script>
        function addToCart() {
            alert("Dodano do koszyka!");
        }
    </script>
</head>
<body>
    
<?php include 'nav.php'; ?>

    <div class="about-us">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        
        <!-- Długi opis produktu -->
        <p><?php echo nl2br(htmlspecialchars($product['long_description'])); ?></p>
        
        <!-- Zdjęcie produktu -->
        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>" 
             style="max-width: 100%; margin: 9% auto; display: block;">

        <div class="content-row">
            <div class="content-box">
                <h2>Składniki aktywne</h2>
                <p><?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p>
            </div>
            <div class="content-box">
                <h2>Działanie</h2>
                <p><?php echo nl2br(htmlspecialchars($product['effects'])); ?></p>
            </div>
        </div>

        <h3 style="text-align: center;">
            Cena: <?php echo number_format($product['price'], 2, ',', ' '); ?> zł
        </h3>

        <div style="text-align: center; margin-top: 20px;">
       

        <form action="add_to_cart.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $productId; ?>">
    <input type="hidden" name="action" id="action" value="add">

    <button type="submit" class="button" onclick="document.getElementById('action').value='add'">Dodaj do koszyka</button>
    <button type="submit" class="button" onclick="document.getElementById('action').value='buy'">Kup teraz</button>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
