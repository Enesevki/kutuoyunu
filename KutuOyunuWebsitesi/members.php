<?php
session_start();
include 'navbar_premium.php';

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
}

// Üyeleri veritabanından çekiyoruz
$query = "SELECT id, username, membership_type FROM users";
$result = $mysqli->query($query);

$members = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
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
    <title>Üyeler</title>
</head>
<body>
     <!-- Bootstrap alert -->
     <?php if (isset($_SESSION['delete_success'])): ?>
        <?php if ($_SESSION['delete_success']): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Üye başarıyla silindi.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Üye silinirken bir hata oluştu.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php unset($_SESSION['delete_success']); ?>
    <?php endif; ?>
    <div class="container mt-5">
        <h2>Üyeler</h2>
        <?php if (count($members) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kullanıcı Adı</th>
                        <th>Üyelik Tipi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['username']); ?></td>
                            <td><?php echo htmlspecialchars($member['membership_type']); ?></td>
                            <td>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteMemberModal<?php echo $member['id']; ?>">Üye Sil</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Hiç üye bulunmamaktadır.</p>
        <?php endif; ?>
    </div>

    <!-- Delete Member Modal -->
    <?php foreach ($members as $member): ?>
        <div class="modal fade" id="deleteMemberModal<?php echo $member['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteMemberModalLabel<?php echo $member['id']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMemberModalLabel<?php echo $member['id']; ?>">Üyeyi Silmek İstiyor musunuz?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="delete_member.php">
                            <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                            <button type="submit" class="btn btn-danger">Evet</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hayır</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
