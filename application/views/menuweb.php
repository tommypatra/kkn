<nav class="main-navbar">
    <div class="container">
        <ul>
            <li class="menu-item  ">
                <a href="<?= base_url() ?>" class='menu-link'>
                    <i class="bi bi-house-heart-fill"></i>
                    <span>Halaman Depan</span>
                </a>
            </li>
            <li class="menu-item active has-sub">
                <a href="#" class='menu-link'>
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Profil</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            <li class="submenu-item  ">
                                <a href="<?= base_url("web/profil/1") ?>" class='submenu-link'>Visi Misi</a>
                            </li>
                            <li class="submenu-item  ">
                                <a href="<?= base_url("web/profil/2") ?>" class='submenu-link'>Tujuan</a>
                            </li>
                            <li class="submenu-item  ">
                                <a href="<?= base_url("web/profil/3") ?>" class='submenu-link'>Struktur Organisasi</a>
                            </li>
                    </div>
                </div>
            </li>
            <li class="menu-item  ">
                <a href="<?= base_url('rilis') ?>" class='menu-link'>
                    <i class="bi bi-stack"></i>
                    <span>Rilis <?= $this->config->item('app_singkatan') ?></span>
                </a>
            </li>
            <li class="menu-item  has-sub">
                <a href="#" class='menu-link'>
                    <i class="bi bi-newspaper"></i>
                    <span>Publikasi</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            <li class="submenu-item  ">
                                <a href="<?= base_url('berita') ?>" class='submenu-link'>Berita</a>
                            </li>
                            <li class="submenu-item  ">
                                <a href="<?= base_url('web/dokumen/files') ?>" class='submenu-link'>Download</a>
                            </li>
                            <li class="submenu-item  ">
                                <a href="<?= base_url('web/dokumen/images') ?>" class='submenu-link'>Gallery</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

            <li class="menu-item  has-sub">
                <a href="#" class='menu-link'>
                    <i class="bi bi-person-heart"></i>
                    <span>Akun</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            <?php if ($this->session->userdata('iduser')) { ?>
                                <li class="submenu-item  ">
                                    <a href="<?= base_url("app/dashboard") ?>" class='submenu-link'>Dashboard</a>
                                </li>
                                <?php
                                $grupakun = json_decode($this->session->userdata('idgrup'), true);
                                if (in_array(4, $grupakun)) { ?>
                                    <li class="submenu-item  ">
                                        <a href="<?= base_url("mahasiswa/penempatan/lkhmahasiswa") ?>" class='submenu-link'>LKH <?= $this->config->item('app_singkatan') ?></a>
                                    </li>
                                <?php } ?>

                                <li class="submenu-item  ">
                                    <a href="<?= base_url("akun") ?>" class='submenu-link'>Identitas</a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="<?= base_url("logout") ?>" class='submenu-link'>Keluar</a>
                                </li>
                            <?php } else { ?>
                                <li class="submenu-item  ">
                                    <a href="<?= base_url("login") ?>" class='submenu-link'>Login</a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="<?= base_url("daftar") ?>" class='submenu-link'>Mendaftar</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </li>


            <li class="menu-item  has-sub">
                <a href="#" class='menu-link'>
                    <i class="bi bi-life-preserver"></i>
                    <span>Bantuan</span>
                </a>
                <div class="submenu ">
                    <!-- Wrap to submenu-group-wrapper if you want 3-level submenu. Otherwise remove it. -->
                    <div class="submenu-group-wrapper">
                        <ul class="submenu-group">
                            <li class="submenu-item  ">
                                <a href="<?= $this->config->item('link_dokumentasi') ?>" class='submenu-link'>Dokumentasi</a>
                            </li>
                            <li class="submenu-item  ">
                                <a href="<?= $this->config->item('link_youtube') ?>" class='submenu-link'>Video Youtube</a>
                            </li>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>