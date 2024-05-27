<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="profile.php">Profil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#userInfoModal">Bilgileri Görüntüle</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="editInfo">Bilgileri Değiştir</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="game_review.php">Oyun İncele</a>
            </li>
            </li><li class="nav-item">
                <a class="nav-link" href="my_reviews.php">İncelemelerim</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="equipment_list.php">Ekipmanları Listele</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <?php if ($_SESSION['membership_type'] == 'Standard'): ?>
                <li class="nav-item">
                    <button id="premiumButton" class="nav-link btn btn-link">Premium Ol</button>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Çıkış Yap</a>
            </li>
        </ul>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+6bW/z3qLm0WIOk/2Orz4BmfE4EhFfAvC6CKti6" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#premiumButton').click(function(){
            var password = prompt("Lütfen şifrenizi girin:(şifre: admin)");
            if(password === "admin") {
                $('#premiumForm').submit();
            } else {
                alert("Geçersiz şifre. Lütfen tekrar deneyin.");
            }
        });
    });
</script>

<form id="premiumForm" action="upgrade_to_premium.php" method="post">
    <input type="hidden" name="adminPassword" value="admin">
    <input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
</form>

