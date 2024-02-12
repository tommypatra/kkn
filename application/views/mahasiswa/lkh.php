<?php
$title = "LKH Mahasiswa " . $ispeserta['tema'] . " (" . $ispeserta['tahun'] . ") ";
$title .= "Nama : " . $ispeserta['nama'] . " ";
$title .= "(Nim " . $ispeserta['nim'] . "/ Prodi " . $ispeserta['prodi'] . " ) ";
$title .= "Kelompok " . $ispeserta['namakelompok'] . " desa " . $ispeserta['desa'];
?>
<input type="hidden" id="idpenempatan" name="idpenempatan" value="<?= $idpenempatan ?>">
<input type="hidden" id="titleweb" name="titleweb" value="<?= $title ?>">
<input type="hidden" id="ketkkn" name="ketkkn" value="<?= $ispeserta['ketkkn'] ?>">

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
            <h4 class="card-title d-flex">LKH Mahasiswa</h4>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <?php if ($ispeserta['ketkkn'] == "terbuka") { ?>
                        <a href="#" class="btn icon btn-success addPage"><i class="bi bi-plus-circle"></i></a>
                    <?php } ?>
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-jadwal">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col"><input type="checkbox" class="cekSemua"></th>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Jenis/ Uraian Kegiatan/ Keterlibatan</th>
                            <th style="vertical-align: middle;" scope="col">Est. Biaya</th>
                            <th style="vertical-align: middle;" scope="col">Dokumentasi</th>
                            <th style="vertical-align: middle;" scope="col">Waktu</th>
                            <th style="vertical-align: middle;" scope="col">Aksi</th>
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
<div class="modal fade text-left w-100" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Form <?= $web['title'] ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formweb">
                <input type="hidden" id="id" name="id">
                <div class="modal-body">


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Waktu</label>
                                <input type="text" class="form-control datepicker validate[required]" id="waktu" name="waktu" value="<?= date('Y-m-d H:i:s') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Uraian Kegiatan</label>
                                <div class="form-control" id="uraian" name="uraian"></div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- full size modal-->