<?php
session_start();
include 'navbar_premium.php';

$message = ''; // Initialize an empty message variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['equipment_name']) && isset($_POST['description'])) {
    $equipment_name = $_POST['equipment_name'];
    $description = $_POST['description'];
    $status = 'Mevcut'; // Automatically set status to "Mevcut"

    // Database connection
    $mysqli = new mysqli("localhost", "dbusr21360859079", "WrAE8zOmcb88", "dbstorage21360859079");

    if ($mysqli->connect_error) {
        die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
    }

    // Insert equipment into the database
    $query = "INSERT INTO equipment (equipment_name, description, status) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $equipment_name, $description, $status);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                      Ekipman başarıyla eklendi!
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      Ekipman eklenirken hata oluştu: ' . $stmt->error . '
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Ekipman Ekle</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Ekipman Ekle</h2>
        <?php echo $message; ?> <!-- Display the message here -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="equipment_name">Ekipman Adı:</label>
                <input type="text" class="form-control" id="equipment_name" name="equipment_name" required>
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ekipmanı Ekle</button>
        </form>
    </div>
</body>
</html>
