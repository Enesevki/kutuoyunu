<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $username = $_POST['username'];
    $adminPassword = $_POST['adminPassword'];

    // Şifrenin doğruluğunu kontrol et
    if ($adminPassword === 'admin') {
        // Veritabanı bağlantısı
        $mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

        if ($mysqli->connect_error) {
            die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
        }

        // Kullanıcının üyelik tipini güncelle
        $query = "UPDATE users SET membership_type = 'Premium' WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $_SESSION['membership_type'] = 'Premium'; // Oturumdaki üyelik tipini güncelle
            header("Location: profile.php");
            exit();
        } else {
            echo "Güncelleme sırasında hata oluştu: " . $stmt->error;
        }

        $stmt->close();
        $mysqli->close();
    } else {
        echo "Yanlış şifre!";
    }
} else {
    header("Location: members.php");
    exit();
}
?>
