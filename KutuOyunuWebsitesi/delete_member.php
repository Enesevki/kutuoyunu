<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['member_id'])) {
    // Veritabanı bağlantısı
    $mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

    if ($mysqli->connect_error) {
        die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
    }

    // ID'yi belirle
    $memberId = $_POST['member_id'];

    // Üye silme sorgusu
    $query = "DELETE FROM users WHERE id = ?";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $memberId);
    $statement->execute();

    // Başarılı bir şekilde silindiğinden emin ol
    if ($statement->affected_rows > 0) {
        $_SESSION['delete_success'] = true;
    } else {
        $_SESSION['delete_success'] = false;
    }

    $statement->close();
    $mysqli->close();
}

// Members sayfasına geri dön
header("Location: members.php");
exit();
?>
