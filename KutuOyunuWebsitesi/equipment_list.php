<?php
session_start();
include 'navbar_standart.php';

// Veritabanı bağlantısı
$mysqli = new mysqli("localhost", "root", "", "dbstorage21360859079");

if ($mysqli->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
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
    <title>Ekipman Listesi</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ekipman Listesi</h2>
        <div id="alertPlaceholder"></div>
        <table class="table">
            <thead>
                <tr>
                    <th>İsim</th>
                    <th>Açıklama</th>
                    <th>Durum</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['equipment_name'] . "</td>";
                    echo "<td>" . $row['description'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
                    if ($row['status'] == 'Mevcut') {
                        echo "<button class='btn btn-primary borrowBtn' data-id='" . $row['id'] . "'>Ödünç Al</button>";
                    } else {
                        echo "<button class='btn btn-primary' disabled>Ödünç Al</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Ödünç Al Pop-up -->
    <div id="borrowModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ekipman Ödünç Alma</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Ekipmanı kaç gün süreyle ödünç almak istiyorsunuz? (Maksimum 15 gün)</p>
                    <form id="borrowForm">
                        <div class="form-group">
                            <label for="duration">Süre (gün cinsinden)</label>
                            <input type="number" class="form-control" id="duration" name="duration" max="15" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Vazgeç</button>
                    <button type="button" class="btn btn-primary" id="confirmBorrow">Evet, Ödünç Al</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".borrowBtn").click(function(){
                var equipmentId = $(this).data('id');
                $('#borrowModal').modal('show');

                // Ödünç al butonuna tıklanırsa
                $("#confirmBorrow").off("click").click(function(){
                    var duration = $("#duration").val();
                    
                    // Süre kontrolü
                    if (duration > 15) {
                        var alertMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                           'Ödünç alma süresi maksimum 15 gün olabilir.' +
                                           '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                           '<span aria-hidden="true">&times;</span>' +
                                           '</button>' +
                                           '</div>';
                        $("#alertPlaceholder").html(alertMessage);
                        return;
                    }

                    $('#borrowModal').modal('hide');

                    // AJAX ile ödünç alma işlemini gerçekleştir
                    $.ajax({
                        type: "POST",
                        url: "borrow_equipment.php",
                        data: { id: equipmentId, duration: duration },
                        dataType: "json",
                        success: function(response) {
                            var alertType = response.status === "success" ? "alert-success" : "alert-danger";
                            var alertMessage = '<div class="alert ' + alertType + ' alert-dismissible fade show" role="alert">' +
                                               response.message +
                                               '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                               '<span aria-hidden="true">&times;</span>' +
                                               '</button>' +
                                               '</div>';
                            $("#alertPlaceholder").html(alertMessage);
                            if (response.status === "success") {
                                setTimeout(function() {
                                    location.reload();
                                }, 2000); // 2 saniye sonra sayfayı yenile
                            }
                        },
                        error: function(xhr, status, error) {
                            var alertMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                               'Hata: ' + xhr.responseText +
                                               '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                               '<span aria-hidden="true">&times;</span>' +
                                               '</button>' +
                                               '</div>';
                            $("#alertPlaceholder").html(alertMessage);
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>

<?php
$mysqli->close();
?>