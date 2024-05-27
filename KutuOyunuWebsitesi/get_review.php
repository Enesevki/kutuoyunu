<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Geçersiz istek.']);
    exit();
}

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "root", "", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

// İncelemeyi çekme
$review_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$query = "SELECT id, oyun_ad, derecelendirme, yorum FROM reviews WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $review_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();

$stmt->close();
$mysqli->close();

echo json_encode($review);
?>
