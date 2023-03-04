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
            <div class="card-title d-flex">
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Daftar <?= $web['title'] ?>
            </div>
            <div class="list-inline d-flex menuweb">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success addPage"><i class="bi bi-plus-circle"></i></a>
                    <a href="#" class="btn icon btn-primary refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-data">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col"><input type="checkbox" class="cekSemua"></th>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Grup</th>
                            <th style="vertical-align: middle;" scope="col">Tabel Referensi</th>
                            <th style="vertical-align: middle;" scope="col">Aktifasi Otomatis</th>
                            <th style="vertical-align: middle;" scope="col">Status Registrasi</th>
                            <th style="vertical-align: middle;" scope="col">Keterangan</th>
                            <th style="vertical-align: middle;" scope="col">Status Aktif</th>
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
<div class="modal fade text-left w-100" id="modal-form-web" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-default" role="document">
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
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nama Grup</label>
                                <input type="text" class="form-control validate[required]" id="nama_grup" name="nama_grup">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Table Referensi</label>
                                <input type="text" class="form-control validate[required]" id="tableref" name="tableref">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Aktivasi Otomatis</label>
                                <select class="form-select validate[required]" id="self_activated" name="self_activated"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status Registrasi</label>
                                <select class="form-select validate[required]" id="reg" name="reg"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Aktif</label>
                                <select class="form-select validate[required]" id="aktif" name="aktif"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Ket</label>
                                <input type="text" class="form-control" id="ket" name="ket">
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