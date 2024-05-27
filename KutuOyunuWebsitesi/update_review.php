<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $review_id = $_POST['review_id']; // Düzenlenecek review_id'yi formdan al
    $derecelendirme = $_POST['edit_derecelendirme'];
    $yorum = $_POST['edit_yorum'];

    // Veritabanı bağlantısı
    $mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

    if ($mysqli->connect_error) {
        die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
    }

    // Veriyi güncelle
    $query = "UPDATE reviews SET derecelendirme = ?, yorum = ? WHERE review_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die("Sorgu hatası: " . $mysqli->error);
    }

    $stmt->bind_param("isi", $derecelendirme, $yorum, $review_id);
    if (!$stmt->execute()) {
        die("Sorgu çalıştırma hatası: " . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();

    // Başarılı güncelleme durumunda kullanıcıyı yönlendir
    header("Location: my_reviews.php");
    exit();
} else {
    // Post metodu ile gelmemişse hata ver
    die("Form verileri post edilmedi.");
}
?>
