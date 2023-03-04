<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Profil Pembimbing</h3>
            <p class="text-subtitle text-muted" id="labeljudul">Data Pembimbing</p>
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
                        <input type="hidden" id="idpembimbing" name="idpembimbing" value="">
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
                                        <input type="text" id="nama" readonly class="form-control" placeholder="Nama Lengkap" name="nama">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="nik" class="sr-only">NIK</label>
                                        <input type="text" id="nik" readonly class="form-control" placeholder="NIK" name="nik">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="kel" class="sr-only">Jenis Kelamin</label>
                                        <input type="text" id="kel" readonly class="form-control" placeholder="Jenis Kelamin" name="kel">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="sr-only">Email</label>
                                        <input type="email" id="email" class="form-control" readonly placeholder="Email" name="email">
                                    </div>
                                </div>
                            </div>

                            <h5>Data Pembimbing</h5>
                            <p class="card-text">
                                Mohon lengkapi data anda dengan benar, Jika terjadi kesalahan data, admin tidak bertanggung jawab.
                            </p>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-4 col-5">
                                            <label class="col-form-label">Status</label>
                                        </div>
                                        <div class="col-lg-4 col-5">
                                            <select class="form-select validate[required]" id="statuspeg" name="statuspeg"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-4 col-5">
                                            <label class="col-form-label">NIP/NIDN</label>
                                        </div>
                                        <div class="col-lg-6 col-5">
                                            <input type="text" id="nip" class="form-control validate[required]" name="nip" placeholder="nip/nidn">
                                        </div>
                                        <div class="col-12">
                                            <i>Catatan :
                                                NIP wajib bagi PNS, dan NIDK bagi Non PNS
                                            </i>
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
                                        <li>Jika terdapat kesalahan maka saya <b>bersedia bertanggung jawab dan tidak menyalahkan siapapun</b>;</li>
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