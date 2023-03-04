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

<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title d-flex">
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Tabel <?= $web['title'] ?>
            </h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-select" id="statusmhs" name="statusmhs">
                                <option value="all">Semua</option>
                                <option value="peserta">Peserta</option>
                                <option value="pendaftar">Pendaftar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-sm table-jadwal">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Foto</th>
                            <th style="vertical-align: middle;" scope="col">Nama/ Nim</th>
                            <th style="vertical-align: middle;" scope="col">Fakultas/ Program Studi</th>
                            <th style="vertical-align: middle;" scope="col">Jenis Kelamin</th>
                            <th style="vertical-align: middle;" scope="col">Verifikasi/ Status/ No. HP</th>
                            <th style="vertical-align: middle;" scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-form-kelengkapan" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Kelengkapan Berkas</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formverifikasi">
                <input type="hidden" id="fidkkn">
                <input type="hidden" id="fidpendaftar">
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="fmahasiswa"></div>
                        <div class="fkelengkapan">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                Tetapkan Sebagai Peserta
                            </div>
                            <div class="col-md-4">
                                <?php
                                $status = [
                                    ['val' => '0', 'label' => 'Tidak'],
                                    ['val' => '1', 'label' => 'Ya'],
                                ];
                                ?>
                                <select class="form-select validate[required]" id="statuspeserta" name="statuspeserta">
                                    <?php
                                    foreach ($status as $i => $dp) {
                                        echo '<option value="' . $dp['val'] . '">' . $dp['label'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success simpanVerifikasi">
                        Simpan
                    </button>
                    <div class="btn btn-danger hapusVerifikasi">
                        Hapus Verifikasi
                    </div>
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal web -->