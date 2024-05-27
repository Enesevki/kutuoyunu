<?php
session_start();

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

// Kullanıcı oturumu açıldıysa, kullanıcı ID'sini al
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Eğer POST isteği geldiyse ve id set edilmişse
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['duration'])) {
        $equipment_id = $_POST['id'];
        $duration = $_POST['duration']; // Süreyi gün cinsinden al

        // Süreyi 15 gün ile sınırlayalım
        if ($duration > 15) {
            echo json_encode(["status" => "error", "message" => "Ödünç alma süresi maksimum 15 gün olabilir."]);
            exit;
        }

        // Ödünç alma işlemini gerçekleştir
        $updateQuery = "UPDATE equipment SET status='Ödünç Verilmiş', borrowed_by=?, borrowed_duration=? WHERE id=?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param("iii", $user_id, $duration, $equipment_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Ekipman başarıyla ödünç alındı!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Ekipman ödünç alınırken hata oluştu: " . $mysqli->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Geçersiz istek."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Kullanıcı oturumu açık değil!"]);
}

$mysqli->close();
?>
