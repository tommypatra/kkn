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

        <div class=" card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-data">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">DPL</th>
                            <th style="vertical-align: middle;" scope="col">Kelompok</th>
                            <th style="vertical-align: middle;" scope="col">Data Mahasiswa</th>
                            <th style="vertical-align: middle;" scope="col">Nilai Pembimbing</th>
                            <th style="vertical-align: middle;" scope="col">Nilai LPPM</th>
                            <th style="vertical-align: middle;" scope="col">Nilai Akhir</th>
                            <th style="vertical-align: middle;" scope="col">Nama Kelompok</th>
                            <th style="vertical-align: middle;" scope="col">DPL</th>
                            <th style="vertical-align: middle;" scope="col">Lokasi</th>
                            <th style="vertical-align: middle;" scope="col">No. Urut</th>
                            <th style="vertical-align: middle;" scope="col">Nama</th>
                            <th style="vertical-align: middle;" scope="col">NIM</th>
                            <th style="vertical-align: middle;" scope="col">Prodi</th>
                            <th style="vertical-align: middle;" scope="col">Jabatan</th>
                            <th style="vertical-align: middle;" scope="col">Nilai Pembimbing</th>
                            <th style="vertical-align: middle;" scope="col">Nilai LPPM</th>
                            <th style="vertical-align: middle;" scope="col">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <button class="btn btn-danger hapusTerpilih"><i class="bi bi-trash"></i> Hapus Terpilih</button>
            </div>
        </div>
    </div>
</section>

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-form-peserta" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Pilih Peserta KKN</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-peserta">
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle;" scope="col"><input type="checkbox" class="cekSemua2"></th>
                                    <th style="vertical-align: middle;" scope="col">No</th>
                                    <th style="vertical-align: middle;" scope="col">Mahasiswa Peserta KKN</th>
                                    <th style="vertical-align: middle;" scope="col">Jabatan</th>
                                    <th style="vertical-align: middle;" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class="data-peserta"></tbody>
                        </table>
                    </div>

                </div>
                <button type="submit" class="btn btn-success btn-simpan-penempatan">
                    Simpan
                </button>
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>


        </div>
    </div>
</div>
<!-- modal web -->

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-form-jabatan" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Ganti Jabatan KKN</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formjabatan">
                <div class="modal-body">
                    <select class='form-select validate[required]' id='idjabatanganti' name='idjabatanganti'>
                        <option value=''>- Pilih -</option>
                        <?php
                        if (count($mstjabatan['db']) > 0)
                            foreach ($mstjabatan['db'] as $i => $dp) { ?>
                            <option value='<?= $dp['id'] ?>'><?= $dp['jabatan'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-simpan-jabatan">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </form>


        </div>
    </div>
</div>
<!-- modal web -->

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-form-kelompok" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Pindah Kelompok</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formkelompok">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            Peserta KKN
                            <select class='form-select validate[required]' id='idpesertaganti' name='idpesertaganti'>
                            </select>
                        </div>
                        <div class="col-md-12">
                            Kelompok
                            <select class='form-select validate[required]' id='idkelompokganti' name='idkelompokganti'>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-simpan-jabatan">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </form>


        </div>
    </div>
</div>
<!-- modal web -->