<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Kayıt Ol</h2>
                <form action="register.php" method="post">
                    <div class="mb-3">
                        <label for="reg_username" class="form-label">Kullanıcı Adı:</label>
                        <input type="text" class="form-control" id="reg_username" name="reg_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_email" class="form-label">E-posta:</label>
                        <input type="email" class="form-control" id="reg_email" name="reg_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_fullname" class="form-label">Ad Soyad:</label>
                        <input type="text" class="form-control" id="reg_fullname" name="reg_fullname" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_gsm" class="form-label">GSM:</label>
                        <input type="text" class="form-control" id="reg_gsm" name="reg_gsm" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_birthdate" class="form-label">Doğum Tarihi:</label>
                        <input type="date" class="form-control" id="reg_birthdate" name="reg_birthdate" required>
                    </div>
                    <div class="mb-3">
                        <label for="reg_password" class="form-label">Şifre:</label>
                        <input type="password" class="form-control" id="reg_password" name="reg_password" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.html" class="btn btn-secondary">Ana Sayfaya Dön</a>
                        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
                        <a href="login_page.php" class="btn btn-secondary">Giriş Yap</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
