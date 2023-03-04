<ul class="menu">
    <li class="sidebar-title">Layanan Pengelola</li>

    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-bank"></i>
            <span>Konten Web <?= $this->config->item("app_singkatan") ?></span>
        </a>
        <ul class="submenu ">
            <li class="submenu-item ">
                <a href="<?= base_url('app/berita') ?>">Berita</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/profil/1') ?>">Visi Misi</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/profil/2') ?>">Tujuan</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/profil/3') ?>">Struktur Organisasi</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/upload') ?>">Upload Dokumen</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('app/jadwal') ?>" class='sidebar-link'>
            <i class="bi bi-calendar2-week"></i>
            <span>Jadwal Kegiatan <?= $this->config->item("app_singkatan") ?></span>
        </a>
    </li>

    <li class="sidebar-item has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-gear"></i>
            <span>Pengaturan <?= $this->config->item("app_singkatan") ?></span>
        </a>
        <ul class="submenu ">
            <li class="submenu-item ">
                <a href="<?= base_url('app/administrasi') ?>">Administrasi</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/output') ?>">Output</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/lokasi') ?>">Lokasi</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/pembimbing') ?>">Pembimbing</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/kelompok') ?>">Kelompok</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('app/pendaftar') ?>" class='sidebar-link'>
            <i class="bi bi-people-fill"></i>
            <span>Pendaftar</span>
        </a>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('app/penempatan') ?>" class='sidebar-link'>
            <i class="bi bi-map"></i>
            <span>Penempatan</span>
        </a>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('app/monitoring') ?>" class='sidebar-link'>
            <i class="bi bi-activity"></i>
            <span>Monitoring</span>
        </a>
    </li>

    <li class="sidebar-item">
        <a href="<?= base_url('app/penilaian') ?>" class='sidebar-link'>
            <i class="bi bi-trophy"></i>
            <span>Nilai Akhir</span>
        </a>
    </li>

    <li class="sidebar-item  has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-collection-fill"></i>
            <span>Data Rujukan</span>
        </a>
        <ul class="submenu ">
            <li class="submenu-item ">
                <a href="<?= base_url('app/fakultas') ?>">Fakultas</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/prodi') ?>">Program Studi</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/jabatanpeserta') ?>">Jabatan Peserta <?= $this->config->item("app_singkatan") ?></a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/masteroutput') ?>">Output Peserta <?= $this->config->item("app_singkatan") ?></a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/masterpenilai') ?>">Grup Penilai</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/provinsi') ?>">Provinsi</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/kabupaten') ?>">Kabupaten</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/kecamatan') ?>">Kecamatan</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/desa') ?>">Desa</a>
            </li>
        </ul>
    </li>



    <li class="sidebar-item  has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-shield-lock"></i>
            <span>Pengaturan System</span>
        </a>
        <ul class="submenu ">
            <li class="submenu-item ">
                <a href="<?= base_url('app/grup') ?>">Grup</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/module') ?>">Module Web</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/menu') ?>">Menu Web</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/hakakses') ?>">Akses Grup</a>
            </li>
        </ul>
    </li>


    <li class="sidebar-item  has-sub">
        <a href="#" class='sidebar-link'>
            <i class="bi bi-person-lines-fill"></i>
            <span>Pengguna Aplikasi</span>
        </a>
        <ul class=" submenu ">
            <li class="submenu-item ">
                <a href="<?= base_url('app/akun') ?>">Akun</a>
            </li>
            <li class=" submenu-item ">
                <a href=" <?= base_url('app/admingrup') ?>">Admin</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/pembimbinggrup') ?>">Dosen Pembimbing</a>
            </li>
            <li class="submenu-item ">
                <a href="<?= base_url('app/mahasiswagrup') ?>">Mahasiswa</a>
            </li>
        </ul>
    </li>
</ul>