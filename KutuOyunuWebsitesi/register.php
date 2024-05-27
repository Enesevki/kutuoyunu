<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['reg_username'];
    $email = $_POST['reg_email'];
    $fullname = $_POST['reg_fullname'];
    $gsm = $_POST['reg_gsm'];
    $birthdate = $_POST['reg_birthdate'];
    $password = $_POST['reg_password'];

    // Şifreyi hashleme
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $mysqli = new mysqli("localhost", "root", "", "dbstorage21360859079");

    if ($mysqli->connect_error) {
        die("Veritabanına bağlanırken hata oluştu: " . $mysqli->connect_error);
    }

    // Veritabanına kullanıcı ekleme
    $query = "INSERT INTO users (username, email, first_name, gsm_no, birth_date, password_hash)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssss", $username, $email, $fullname, $gsm, $birthdate, $passwordHash);

    if ($stmt->execute() === TRUE) {
        // Kayıt başarılı, kullanıcıyı giriş sayfasına yönlendir
        header("Location: login_page.php?message=" . urlencode("Kayıt başarılı! Güvenlik nedeniyle tekrar giriş yapmalısınız."));
        exit();
    } else {
        echo "Kayıt işlemi sırasında hata oluştu: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
