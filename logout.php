<?php
session_start();
session_destroy();
header("Location: strona_glowna.php");
exit();
?>
