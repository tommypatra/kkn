<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $web['title'] ?></h3>
            <p class="text-subtitle text-muted">Pendaftaran <?= $this->config->item('app_singkatan') ?> Online dalam sistem terpadu</p>
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


<section id="content-types">
    <div class="row">

        <?php
        //print_r($dataSql);
        if (count($dataSql) > 0) {
            foreach ($dataSql as $index => $dp) {
        ?>
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-content jadwal-kkn">
                            <div class="card-body">
                                <h4 class="card-title"><?= $dp['tema'] . " (" . $dp['jenis'] . ")" ?></h4>
                                <p class="card-text">
                                    <i><?= "Lokasi KKN " . $dp['tempat'] ?></i>
                                <h5><?= $this->config->item('app_singkatan') ?></h5>
                                <ul>
                                    <?php
                                    if ($dp['ketpublishkelompok'] == "terbuka") {
                                        echo "<li>Jabatan  <b>" . $dp['jabatan'] . "</b></li>";
                                        echo "<li>Kelompok  " . $dp['namakelompok'] . " (" . $dp['desa'] . ")</li>";
                                    }
                                    echo "<li>Pelaksanaan  " . $dp['kknmulai'] . " sd " . $dp['kknselesai'] . " " . labeltanggal($dp['kknmulai'], $dp['kknselesai'])['labelbadge'] . "</li>";
                                    ?>
                                </ul>

                                <div class="btn-group mb-3" role="group">
                                    <a href="<?= base_url('dashboard/personal/' . $dp['idpenempatan']) ?>" class="btn btn-primary"><i class="bi bi-journal-album"></i> DASHBOARD LKH</a>
                                    <a href="<?= base_url('mahasiswa/lkh/' . $dp['idpenempatan']) ?>" class="btn btn-success"><i class="bi bi-book"></i> DATA LKH</a>
                                    <a href="#" class="btn btn-secondary upload-output" data-idkkn="<?= $dp['idkkn'] ?>" data-idpenempatan="<?= $dp['idpenempatan'] ?>"><i class="bi bi-upload"></i> Output</a>
                                </div>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            <?php
            }
        } else {
            ?>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <p class="card-text">
                                Belum ada
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>

</section>

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-form-output" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Upload Output</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formoutput">
                <div class="modal-body">
                    <input type="hidden" id="idjadwal" name="idjadwal">
                    <div class="modal-body">
                        <div class="foutput"></div>
                    </div>
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </form>


        </div>
    </div>
</div>
<!-- full size modal-->

<!-- Modal url web -->
<div class="modal fade text-left w-100" id="modal-upload-url" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title" id="myModalForm">Upload Output</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formurl">
                <div class="modal-body">
                    <input type="hidden" id="idjadwal" name="idjadwal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control validate[required,custom[url]]" id="url" name="url">
                                <i>paste url file laporan anda pada input text di atas, contoh : https://kkn.iainpare.ac.id/file/laporanku.pdf</i>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span>Simpan</span>
                    </button>

                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
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
            <div class="modal-header bg-primary">
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