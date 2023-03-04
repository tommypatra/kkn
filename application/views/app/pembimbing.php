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
                    <a href="#" class="btn icon btn-primary addPage"><i class="bi bi-plus-circle"></i></a>
                    <a href="#" class="btn icon btn-primary togglefilter"><i class="bi bi-file-earmark-text"></i></a>
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
            <form class="filter" id="formskpembimbing" style="display:none">
                <hr>
                <h4 class="card-title d-flex">
                    <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Data SK Pembimbing <?= $this->config->item("app_singkatan") ?>
                </h4>

                <input type="hidden" id="idsk_pembimbing" name="idsk_pembimbing">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>No. SK</label>
                            <input type="text" class="form-control validate[required]" id="sk_no" name="sk_no">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal SK</label>
                            <input type="text" class="form-control validate[required] datepicker" id="sk_tgl" name="sk_tgl">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Lampiran SK (Pdf)</label>
                            <div id="detupload"></div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <div class="btn btn-primary ml-1 btn-upload">
                                <i class="bi bi-cloud-upload-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success ml-1 ">
                                <i class="bi bi-usb-drive"></i>
                            </button>
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
                            <th style="vertical-align: middle;" scope="col">Foto</th>
                            <th style="vertical-align: middle;" scope="col">Nama</th>
                            <th style="vertical-align: middle;" scope="col">Jenis Kelamin</th>
                            <th style="vertical-align: middle;" scope="col">Email/ HP</th>
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
<div class="modal fade text-left w-100" id="modal-form-pembimbing" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Form <?= $web['title'] ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formpembimbing">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Peimbimbing</label>
                                <select class="form-select validate[required] vselect2" id="iduser_pembimbing" name="iduser_pembimbing[]" multiple="multiple"></select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="6"></textarea>
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


<!-- modal upload -->
<div class="modal fade text-left w-100" id="fModalUpload" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Form Upload</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="labelUpload"></div>
                <div id="actions" class="row">
                    <div class="col-lg-6">
                        <div class="btn-group w-100">
                            <span class="btn btn-success col fileinput-button">
                                <i class="fas fa-plus"></i>
                                <span>Pilih File</span>
                            </span>
                            <button type="submit" class="btn btn-primary col start">
                                <i class="fas fa-upload"></i>
                                <span>Mulai upload</span>
                            </button>
                            <button type="reset" class="btn btn-warning col cancel">
                                <i class="fas fa-times-circle"></i>
                                <span>Batal upload</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center">
                        <div class="fileupload-process w-100">
                            <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table table-striped files" id="previews">
                    <div id="template" class="row mt-2">
                        <div class="col-auto">
                            <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                        </div>
                        <div class="col d-flex align-items-center">
                            <p class="mb-0">
                                <span class="lead" data-dz-name></span>
                                (<span data-dz-size></span>)
                            </p>
                            <strong class="error text-danger" data-dz-errormessage></strong>
                        </div>
                        <div class="col-4 d-flex align-items-center">
                            <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <div class="btn-group">
                                <button class="btn btn-primary start">
                                    <i class="fas fa-upload"></i>
                                    <span>Mulai</span>
                                </button>
                                <button data-dz-remove class="btn btn-warning cancel">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Batal</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->