<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Profil Mahasiswa</h3>
            <p class="text-subtitle text-muted" id="labeljudul">Data mahasiswa</p>
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

        <div class="card">
            <div class="card-content">
                <div class="card-body">

                    <form id="formidentitas">
                        <input type="hidden" id="idmahasiswa" name="idmahasiswa" value="">
                        <input type="hidden" id="idhakakses" name="idhakakses" value="">
                        <div class="form-body">
                            <div class="row">
                                <h5>Data Identitas</h5>
                                <div class="col-sm-2">
                                    <div class="avatar avatar-xl">
                                        <img src="<?= base_url("assets/img/user-avatar.png") ?>" id="fotoprofil">
                                    </div>
                                </div>

                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label for="nama" class="sr-only">Nama Lengkap</label>
                                        <input type="text" id="nama" class="form-control" placeholder="Nama Lengkap" name="nama" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="nik" class="sr-only">NIK</label>
                                        <input type="text" id="nik" class="form-control" placeholder="NIK" name="nik" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="kel" class="sr-only">Jenis Kelamin</label>
                                        <input type="text" id="kel" class="form-control" placeholder="Jenis Kelamin" name="kel" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="sr-only">Email</label>
                                        <input type="email" id="email" class="form-control" readonly placeholder="Email" name="email">
                                    </div>
                                </div>
                            </div>

                            <h5>Data Mahasiswa</h5>
                            <p class="card-text">
                                Mohon lengkapi data anda dengan benar, Jika terjadi kesalahan data, admin tidak bertanggung jawab.
                            </p>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-4 col-5">
                                            <label class="col-form-label">NIM</label>
                                        </div>
                                        <div class="col-lg-6 col-5">
                                            <input type="text" id="nim" class="form-control validate[required]" name="nim" placeholder="Nim">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-4 col-5">
                                            <label class="col-form-label">Program Studi</label>
                                        </div>
                                        <div class="col-lg-8 col-7">
                                            <select class="form-select validate[required]" id="idprodi" name="idprodi"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="rowktm">
                                <div class="col-md-8">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-4 col-5">
                                            <label class="col-form-label">Upload Kartu Mahasiswa</label>
                                        </div>
                                        <div class="col-lg-8 col-7">
                                            upload gambar KTM maksimum 750kb
                                            <button type="button" class="btn btn-outline-success btn-sm btn-upload" data-iduser=""><i class="bi bi-upload"></i></button>
                                            <img src="<?= base_url("assets/img/kartumhs.png") ?>" id="kartumhs" width="100%">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <input type="checkbox" name="persetujuan" id="persetujuan" class="form-check-input " value="1">
                                    <label for="persetujuan"><b>Pernyataan Setuju bahwa :</b></label>
                                    <ul>
                                        <li>Seluruh data di atas benar;</li>
                                        <li>Jika terdapat kesalahan maka saya <b>bersedia bertanggung jawab, menerima seluruh konsekuensi dan tidak menyalahkan siapapun</b>;</li>
                                        <li>Simpan data mahasiswa <b>hanya dapat dilakukan 1 kali saja</b>, jika salah silahkan <b>membuat akun baru lagi</b>;</li>
                                        <li>Setelah data mahasiswa tersimpan, <b>WAJIB</b> mengupload <b>Kartu Mahasiswa</b>.</li>
                                    </ul>
                                </div>
                            </div>
                            <hr style="margin-top:20px;">
                            <div class="form-actions d-flex justify-content-end" id="tombolform" style="visibility:hidden;">
                                <button type="submit" class="btn btn-success me-1">Simpan</button>
                                <button type="reset" class="btn btn-light-primary">Cancel</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

</section>


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