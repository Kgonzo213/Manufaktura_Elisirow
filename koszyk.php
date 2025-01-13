<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    echo "<script>
        alert('Zaloguj się, aby uzyskać dostęp do koszyka.');
        window.location.href = 'logowanie.php';
    </script>";
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

// Pobierz dane z koszyka użytkownika
$sql = "SELECT c.id AS cart_id, p.id AS product_id, p.name, p.price, c.quantity, 
        (p.price * c.quantity) AS total_price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufaktura Eliksirów - Koszyk</title>
    <link rel="stylesheet" href="styl1.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <header>
        <h1>Twój Koszyk</h1>
    </header>
    
    <button onclick="toggleCartVisibility()" class="button">Pokaż/Ukryj Koszyk</button>

    <div id="cartContainer" style="margin-top: 20px;" class="cart">
        <form action="update_cart.php" method="POST">
            <table id="cartItems">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Cena (zł)</th>
                        <th>Ilość</th>
                        <th>Łączna cena (zł)</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="cart-item" data-cart-id="<?php echo $row['cart_id']; ?>" data-product-id="<?php echo $row['product_id']; ?>" data-price="<?php echo $row['price']; ?>">
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="unit-price"><?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $row['cart_id']; ?>]" class="amount" value="<?php echo (int)$row['quantity']; ?>" min="1">
                            </td>
                            <td class="total-price"><?php echo number_format($row['total_price'], 2); ?></td>
                            <td>
                                <button type="submit" name="action" value="delete_<?php echo $row['cart_id']; ?>" class="remove">Usuń</button>
                            </td>
                        </tr>
                        <?php $total += $row['total_price']; ?>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" style="text-align: right; font-weight: bold;">Suma: <span id="total"><?php echo number_format($total, 2); ?></span> zł</td>
                    </tr>
                </tfoot>
            </table>
            <p><button type="submit" name="action" value="update" id="update-cart" class="button">Zaktualizuj koszyk</button></p>
   

        </form>         
        <button id="checkout" class="button" onclick="proceedToPayment()">Przejdź do płatności</button>
        <script>
        function proceedToPayment() {
            alert('Przechodzisz do płatności');
            window.location.href = 'strona_glowna.php';
        }
        </script>

    </div>

    <footer>
        <p>&copy; 2024 Manufaktura Eliksirów. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
