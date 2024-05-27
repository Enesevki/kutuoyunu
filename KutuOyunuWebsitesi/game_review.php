<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}
include 'navbar_standart.php';

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

$success_message = "";
$error_message = "";

// Kaydetme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oyun_ad = $_POST['oyun_ad'];
    $inceleme = $_POST['inceleme'];
    $derecelendirme = $_POST['derecelendirme'];
    $user_id = $_SESSION['id'];

    // Veritabanına inceleme kaydetme işlemi
    $stmt = $mysqli->prepare("INSERT INTO reviews (user_id, oyun_ad, yorum, derecelendirme) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        $error_message = "Sorgu hatası: " . $mysqli->error;
    } else {
        $stmt->bind_param("isss", $user_id, $oyun_ad, $inceleme, $derecelendirme);
        if ($stmt->execute()) {
            $success_message = "İnceleme başarıyla kaydedildi!";
        } else {
            $error_message = "İnceleme kaydı sırasında hata oluştu: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Ekipmanları veritabanından çekme
$query = "SELECT * FROM equipment";
$result = $mysqli->query($query);

if (!$result) {
    die("Ekipmanları çekerken hata oluştu: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oyun İncele</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Navbar içeriği buraya gelecek -->
    </nav>
    <div class="container mt-5">
        <h2>Oyun İncele</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="oyun_ad">Oyun Adı:</label>
                <select class="form-control" id="oyun_ad" name="oyun_ad" required>
                    <option value="">Lütfen bir oyun seçin</option>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['equipment_name'] . "'>" . $row['equipment_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="inceleme">İnceleme:</label>
                <textarea class="form-control" id="inceleme" name="inceleme" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="derecelendirme">Derecelendirme (1-5 arası):</label>
                <input type="number" class="form-control" id="derecelendirme" name="derecelendirme" min="1" max="5" required>
            </div>
            <button type="submit" class="btn btn-primary">İncelemeyi Kaydet</button>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Başarılı</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $success_message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Hata</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $error_message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            <?php if (!empty($success_message)) { ?>
                $('#successModal').modal('show');
            <?php } elseif (!empty($error_message)) { ?>
                $('#errorModal').modal('show');
            <?php } ?>
        });
    </script>
</body>
</html>

<?php
$mysqli->close();
?>