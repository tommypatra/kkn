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
            <h4 class="card-title d-flex">Pilih Jadwal <?= $web['title'] ?></h4>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success addPage"><i class="bi bi-plus-circle"></i></a>
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>


        <div class="card-body filter">
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
                <table class="table table-striped table-sm table-jadwal">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col"><input type="checkbox" class="cekSemua"></th>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Output</th>
                            <th style="vertical-align: middle;" scope="col">Keterangan</th>
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
<div class="modal fade text-left w-100" id="modal-form-output" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Form <?= $web['title'] ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formoutput">
                <input type="hidden" id="id" name="id">
                <div class="modal-body">


                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Output</label>
                                <select class="form-select validate[required]" id="idoutput" name="idoutput"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control keterangan" id="keterangan" name="keterangan" rows="6"></textarea>
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