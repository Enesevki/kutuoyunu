<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Veritabanı bağlantısı
    $mysqli = new mysqli("localhost", "root", "", "dbstorage21360859079");
    if (!$mysqli) {
        die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
    } else {
        echo "Veritabanı bağlantısı başarılı!";
    }

    if ($mysqli->connect_error) {
        die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
    }

    // Kullanıcıyı veritabanında arama
    $query = "SELECT * FROM users WHERE username=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['id'] = $row['id']; // Kullanıcı ID'sini oturuma ekleyelim
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $row['first_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['gsm'] = $row['gsm_no'];
            $_SESSION['birthdate'] = $row['birth_date'];

            header("Location: profile.php");
            exit();
        } else {
            $error = "Giriş bilgileri hatalı.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }

    $stmt->close();
    $mysqli->close();

    if (isset($error)) {
        header("Location: login_page.php?error=" . urlencode($error));
        exit();
    }
}
?>
