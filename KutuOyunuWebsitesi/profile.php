<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "root", "", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

// Kullanıcı bilgilerini oturumdan alıyoruz
$username = $_SESSION['username'];

// Kullanıcının membership_type bilgisini veritabanından çekiyoruz
$query = "SELECT membership_type FROM users WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $membership_type = $row['membership_type'];
    $_SESSION['membership_type'] = $membership_type;
} else {
    echo "Kullanıcı bilgileri alınamadı.";
    exit();
}

$stmt->close();

// Tüm kullanıcı incelemelerini çekme
$query = "SELECT reviews.yorum, reviews.derecelendirme, reviews.oyun_ad, users.username 
          FROM reviews 
          JOIN users ON reviews.user_id = users.id";
$result = $mysqli->query($query);

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Profil</title>

    <style>
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .github-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .github-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php
    // Kullanıcının membership_type'ına göre navbar dosyasını dahil ediyoruz
    if ($_SESSION['membership_type'] == 'Premium') {
        include 'navbar_premium_profile.php';
    } else {
        include 'navbar_standart_profile.php';
    }
    ?>

    <div class="container mt-5">
        <h2>Hoş Geldiniz, <?php echo $_SESSION['fullname']; ?></h2>

        <!-- Tüm Kullanıcı İncelemeleri -->
        <h3 class="mt-4">Kullanıcı İncelemeleri</h3>
        <?php if (count($reviews) > 0): ?>
            <div class="row">
                <?php foreach ($reviews as $review): ?>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold"><?php echo htmlspecialchars($review['username']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($review['oyun_ad']); ?></h6>
                                <p class="card-text"><strong>Derecelendirme:</strong> <?php echo htmlspecialchars($review['derecelendirme']); ?>/5</p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($review['yorum'])); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Henüz hiçbir kullanıcı inceleme yapmamış.</p>
        <?php endif; ?>
    </div>

    <!-- Bilgileri Göster Modal -->
    <div class="modal fade" id="userInfoModal" tabindex="-1" role="dialog" aria-labelledby="userInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">Kullanıcı Bilgileri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Kullanıcı Adı: <?php echo $_SESSION['fullname']; ?></p>
                    <p>E-posta: <?php echo $_SESSION['email']; ?></p>
                    <p>GSM: <?php echo $_SESSION['gsm']; ?></p>
                    <p>Doğum Tarihi: <?php echo date('d.m.Y', strtotime($_SESSION['birthdate'])); ?></p>
                    <p>Üyelik Tipi: <?php echo  $_SESSION['membership_type']; ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bilgileri Değiştir Modal -->
    <div class="modal fade" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="editInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInfoModalLabel">Bilgileri Değiştir</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editInfoForm" action="update_info.php" method="post">
                        <div class="form-group">
                            <label for="fullname">Ad Soyad</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $_SESSION['fullname']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="gsm">GSM</label>
                            <input type="text" class="form-control" id="gsm" name="gsm" value="<?php echo $_SESSION['gsm']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="birthdate">Doğum Tarihi (Gün.Ay.Yıl)</label>
                            <input type="text" class="form-control" id="birthdate" name="birthdate" value="<?php echo date('d.m.Y', strtotime($_SESSION['birthdate'])); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- GitHub Butonu -->
    <a href="https://github.com/Enesevki/kutuoyunu" class="github-button" target="_blank">GitHub</a>

    <script>
        $(document).ready(function() {
            $('#editInfo').click(function() {
                $('#editInfoModal').modal('show');
            });
        });
    </script>
</body>
</html>
