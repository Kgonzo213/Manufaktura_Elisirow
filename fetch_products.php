<?php
include 'db.php';

$category = $_GET['category'] ?? '';

$stmt = $conn->prepare("SELECT nazwa, cena, opis, image_path FROM produkty WHERE kategoria = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div>
        <h3>{$row['nazwa']}</h3>
        <img src='{$row['image_path']}' alt='{$row['nazwa']}' />
        <p>{$row['opis']}</p>
        <p>Cena: {$row['cena']} zł</p>
    </div>";
}
?>
