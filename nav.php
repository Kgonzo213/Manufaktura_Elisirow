<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <a href="strona_glowna.php" class="button">Nasza Strona Główna</a>
    <a href="galeria.php" class="button">Galeria</a>
    <a href="koszyk.php" class="button">Koszyk</a>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="moj_profil.php" class="button">
            <?php if ($_SESSION['user']['profile_picture']): ?>
            <img src="<?php echo htmlspecialchars($_SESSION['user']['profile_picture']); ?>" alt="Profil" style="height: 30px; border-radius: 50%;">
            <?php endif; ?>
            Witaj, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
        </a>
        <a href="logout.php" class="button">Wyloguj</a>
    <?php else: ?>
        <a href="logowanie.php" class="button">Zaloguj się</a>
    <?php endif; ?>
</nav>