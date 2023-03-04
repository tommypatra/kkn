<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $web['title'] ?> Tahun <?= date("Y") ?></h3>
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
                                    <i><?= "Lokasi " . $dp['tempat'] ?></i>
                                <h5><?= $web['title'] ?></h5>
                                <?= "Pendaftaran  " . $dp['daftarmulai'] . " sd " . $dp['daftarselesai'] . " " . labeltanggal($dp['daftarmulai'], $dp['daftarselesai'])['labelbadge'] ?>
                                <br>
                                <?= "Pelaksanaan  " . $dp['kknmulai'] . " sd " . $dp['kknselesai'] . " " . labeltanggal($dp['kknmulai'], $dp['kknselesai'])['labelbadge'] ?>
                                <?php
                                $dataadm = searchMultiArray($dataSqlAdm, "idkkn", $dp['id']);
                                //$dataadm = isset($dataSqlAdm[$dp['id']]) ? $dataSqlAdm[$dp['id']] : array();
                                //$periksa = false;
                                if (count($dataadm) > 0) {
                                    echo "<h5>Syarat Administrasi</h5>";
                                    echo "<ul>";
                                    foreach ($dataadm as $index => $dt) {
                                        //print_r($dt);
                                        echo "<li>";
                                        echo $dt['namaadministrasi'] . ", ";
                                        echo "<i>" . $dt['keterangan'] . "</i>";
                                        if ($dt['upload_file'] == "y") {
                                            echo " [ Upload " . $dt['upload_type'] . " max " . ($dt['upload_size'] / 1000) . " mb]";
                                        }
                                        /*
                                        if ($dt['status'] != "") {
                                            $periksa = true;
                                            $labelstatus = ($dt['status'] == "MS") ? "success" : "danger";
                                            echo " <span class='badge bg-" . $labelstatus . "'>" . $dt['status'] . "</span>";
                                        }
                                        */
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                }
                                echo "<h5>Keterangan</h5>";
                                echo "<div>" . $dp['keterangan'] . "</div>";

                                //if ($periksa)
                                if ($dp['ispeserta'])
                                    echo "<span class='badge bg-success'>Memenuhi Syarat Sebagai Peserta KKN</span>";
                                //  else
                                //    echo "<span class='badge bg-danger'>Tidak Memenuhi Syarat Sebagai Peserta KKN</span>";

                                ?>
                                </p>
                            </div>
                        </div>
                        <?php //if ($dp['ketpendaftaran']) { 
                        ?>
                        <div class="card-footer d-flex justify-content-between">
                            <?php if (!$dp['idpendaftar'] && $dp['ketpendaftaran']) { ?>
                                <button class="btn btn-light-primary daftar" data-idpendaftar="<?= $dp['idpendaftar'] ?>" data-idkkn="<?= $dp['id'] ?>">Daftar Sekarang!</button>
                            <?php } elseif ($dp['idpendaftar']) { ?>
                                <button class="btn btn-light-success kelengkapan" data-idpendaftar="<?= $dp['idpendaftar'] ?>" data-idkkn="<?= $dp['id'] ?>">Upload Berkas Pendaftaran!</button>
                                <?php if ($dp['ketpendaftaran']) { ?>
                                    <button class="btn btn-light-danger batalkan" data-idpendaftar="<?= $dp['idpendaftar'] ?>" data-idkkn="<?= $dp['id'] ?>">Batalkan!</button>
                            <?php }
                            } else {
                                echo "<i>Tidak ada pelayanan pendaftaran!</i>";
                            }
                            ?>
                        </div>
                        <?php //} 
                        ?>
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
                                Jadwal KKN pada tahun <?= date("Y") ?> belum diatur.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
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
            <form id="formkelengkapan">
                <div class="modal-body">
                    <input type="hidden" id="idjadwal" name="idjadwal">
                    <div class="modal-body">
                        <div class="fkelengkapan"></div>
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