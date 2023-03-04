<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $web['title'] ?></h3>
            <p class="text-subtitle text-muted">Pengaturan <?= $web['title'] ?> pada sistem terintegrasi</p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url("app/dashboard") ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $web['title'] ?></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="card-title d-flex">Pilih Jadwal <?= $this->config->item("app_singkatan") ?></h4>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-primary refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <form id="setupjadwal">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-select" id="setuptahun" name="setuptahun"></select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <select class="form-select" id="idjadwalkkn" name="idjadwalkkn"></select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


<section class="list-group-navigation menu-navigasi" style="display:none;">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Navigasi Monitoring</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4">
                                <div class="list-group" role="tablist">
                                    <a class="list-group-item list-group-item-action active" id="list-posko-list" data-bs-toggle="list" href="#list-posko" role="tab" aria-selected="true">Lokasi Posko</a>
                                    <a class="list-group-item list-group-item-action" id="list-wilayah-list" data-bs-toggle="list" href="#list-wilayah" role="tab" aria-selected="false">Daftar Wilayah</a>
                                    <a class="list-group-item list-group-item-action" id="list-dpl-list" data-bs-toggle="list" href="#list-dpl" role="tab" aria-selected="false">Dosen Pembimbing Lapangan (DPL)</a>
                                    <a class="list-group-item list-group-item-action" id="list-peserta-list" data-bs-toggle="list" href="#list-peserta" role="tab" aria-selected="false">Peserta</a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-8 mt-1">
                                <div class="tab-content text-justify" id="nav-tabContent">
                                    <div class="tab-pane show active" id="list-posko" role="tabpanel" aria-labelledby="list-posko-list">
                                        Digunakan untuk melihat lokasi posko sesuai dengan data yang telah diupload oleh masing-masing kelompok
                                        <hr>
                                        <a href="#" class="btn btn-primary rounded-pill url-posko">Selengkapnya</a>
                                    </div>
                                    <div class="tab-pane" id="list-wilayah" role="tabpanel" aria-labelledby="list-wilayah-list">
                                        Merupakan layanan yang disiapkan untuk bisa memonitoring secara lengkap wilayah yang menjadi tujuan kegiatan pengabdian ini.
                                        <hr>
                                        <a href="#" class="btn btn-primary rounded-pill url-wilayah">Selengkapnya</a>
                                    </div>
                                    <div class="tab-pane" id="list-dpl" role="tabpanel" aria-labelledby="list-dpl-list">
                                        Layanan ini memungkinkan pengelola dapat melihat daftar Dosen Pembimbing Lapangan (DPL) berserta aktifitas yang telah dilakukan oleh DPL yang bersangkutan
                                        <hr>
                                        <a href="#" class="btn btn-primary rounded-pill url-dpl">Selengkapnya</a>
                                    </div>
                                    <div class="tab-pane" id="list-peserta" role="tabpanel" aria-labelledby="list-peserta-list">
                                        Layanan ini memungkinkan pengelola dapat melihat daftar peserta yang telah ditempatkan pada posko masing-masing
                                        <hr>
                                        <a href="#" class="btn btn-primary rounded-pill url-peserta">Selengkapnya</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>