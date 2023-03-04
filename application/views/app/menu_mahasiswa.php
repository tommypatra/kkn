<ul class="menu">
    <li class="sidebar-title">Layanan Mahasiswa</li>

    <li class="sidebar-item">
        <a href="<?= base_url('mahasiswa/profil') ?>" class='sidebar-link'>
            <i class="bi bi-person-lines-fill"></i>
            <span>Profil Mahasiswa</span>
        </a>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('mahasiswa/kkn') ?>" class='sidebar-link'>
            <i class="bi bi-list-check"></i>
            <span>Pendaftaran <?= $this->config->item("app_singkatan") ?></span>
        </a>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('mahasiswa/penempatan') ?>" class='sidebar-link'>
            <i class="bi bi-journal-richtext"></i>
            <span><?= $this->config->item("app_singkatan") ?> Mahasiswa</span>
        </a>
    </li>

</ul>