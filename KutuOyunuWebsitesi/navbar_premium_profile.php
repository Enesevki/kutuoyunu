<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Profil</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#userInfoModal">Bilgileri Görüntüle</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#editInfoModal">Bilgileri Değiştir</a>
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
            <li class="nav-item">
                <a class="nav-link" href="add_equipment.php">Ekipman Ekle</a>
            
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="members.php">Üyeler</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Çıkış Yap</a>
            </li>
        </ul>
    </div>
</nav>
<script>
    $(document).ready(function() {
        // Bilgileri Görüntüle düğmesine tıklanınca
        $('#viewInfoButton').click(function() {
            // Bilgileri görüntüle modelini aç
            $('#userInfoModal').modal('show');
        });

        // Bilgileri Değiştir düğmesine tıklanınca
        $('#editInfoButton').click(function() {
            // Bilgileri düzenle modelini aç
            $('#editInfoModal').modal('show');
        });
    });
</script>
