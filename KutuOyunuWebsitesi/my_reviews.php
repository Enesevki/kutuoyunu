<?php
session_start();
include 'navbar_standart.php';
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "root", "", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

$user_id = $_SESSION['id']; // Kullanıcının oturumundaki user_id'yi alıyoruz

// Kullanıcının incelemelerini çekme
$query = "SELECT review_id, oyun_ad, yorum, derecelendirme FROM reviews WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("Sorgu hatası: " . $mysqli->error);
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Sorgu çalıştırma hatası: " . $stmt->error);
}

$result = $stmt->get_result();

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
    <title>My Reviews</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- Navbar içeriği buraya gelecek -->
    </nav>
    <div class="container mt-5">
        <h3>My Reviews</h3>
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($review['oyun_ad']); ?></h5>
                        <p class="card-text"><strong>Rating:</strong> <?php echo htmlspecialchars($review['derecelendirme']); ?>/5</p>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($review['yorum'])); ?></p>
                        <!-- Düzenleme Butonu -->
                        <a href="#" class="btn btn-primary edit-review" data-toggle="modal" data-target="#editReviewModal" data-reviewid="<?php echo $review['review_id']; ?>">Düzenle</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews found.</p>
        <?php endif; ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" role="dialog" aria-labelledby="editReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">İncelemeyi Düzenle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- İnceleme düzenleme formu -->
                    <form id="editReviewForm" action="update_review.php" method="post">
                        <!-- review_id gizli input -->
                        <input type="hidden" id="review_id" name="review_id"> <!-- Burada review_id değerini saklayacağız -->
                        
                        <!-- Derecelendirme input -->
                        <div class="form-group">
                            <label for="edit_derecelendirme">Derecelendirme</label>
                            <input type="number" class="form-control" id="edit_derecelendirme" name="edit_derecelendirme" min="1" max="5" required>
                        </div>
                        <!-- Yorum input -->
                        <div class="form-group">
                            <label for="edit_yorum">Yorum</label>
                            <textarea class="form-control" id="edit_yorum" name="edit_yorum" rows="3" required></textarea>
                        </div>
                        <!-- Kaydet butonu -->
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript Kodu -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Düzenleme butonuna tıklandığında
            $('.edit-review').click(function() {
                var reviewId = $(this).data('reviewid');
                $('#review_id').val(reviewId); // review_id input alanına değeri atıyoruz

                // Ajax isteği ile inceleme bilgilerini al
                $.ajax({
                    url: 'get_review.php',
                    type: 'GET',
                    data: { id: reviewId },
                    success: function(response) {
                        var review = JSON.parse(response);

                        // Modal içerisindeki formu doldur
                        $('#review_id').val(review.review_id);
                        $('#edit_derecelendirme').val(review.derecelendirme);
                        $('#edit_yorum').val(review.yorum);

                        // Modalı göster
                        $('#editReviewModal').modal('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
