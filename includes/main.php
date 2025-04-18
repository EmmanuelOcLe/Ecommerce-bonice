<?php
    $page = $_GET['page'] ?? 'home';
    $file = "pages/$page.php";
    if (file_exists($file)) {
        include $file;
    } else {
        echo "<p style='color:red'>La página solicitada no existe.</p>";
    }
?>