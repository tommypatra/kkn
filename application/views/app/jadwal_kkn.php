<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $web['title'] ?></h3>
            <p class="text-subtitle text-muted">Pengaturan <?= $web['title'] ?> dalam sistem terpadu</p>
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
            <h4 class="card-title d-flex">
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Filter
            </h4>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success addPage"><i class="bi bi-plus-circle"></i></a>
                    <a href="#" class="btn icon btn-primary togglefilter"><i class="bi bi-funnel"></i></a>
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>


        <div class="card-body filter" style="display: none;">
            <form id="filterTables">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tahun</label>
                            <select class="form-select" id="flt_tahun" name="flt_tahun"></select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Semester</label>
                            <select class="form-select" id="flt_semester" name="flt_semester"></select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Jenis <?= $this->config->item("app_singkatan") ?></label>
                            <select class="form-select" id="flt_jenis" name="flt_jenis"></select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary terapkanFilter"><i class="bi bi-funnel"></i> Terapkan</button>
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
                            <th style="vertical-align: middle;" scope="col">Tahun</th>
                            <th style="vertical-align: middle;" scope="col">Tema</th>
                            <th style="vertical-align: middle;" scope="col">Jenis</th>
                            <th style="vertical-align: middle;" scope="col">Tempat</th>
                            <th style="vertical-align: middle;" scope="col">Pendaftaran</th>
                            <th style="vertical-align: middle;" scope="col">Pelaksanaan</th>
                            <th style="vertical-align: middle;" scope="col">Publikasi Kelompok</th>
                            <th style="vertical-align: middle;" scope="col">Laporan Mahasiswa</th>
                            <th style="vertical-align: middle;" scope="col">Penilaian</th>
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
<div class="modal fade text-left w-100" id="modal-form-jadwal" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-full" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Form <?= $web['title'] ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formjadwal">
                <input type="hidden" id="idjadwal" name="idjadwal">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select class="form-select validate[required] vselect2" id="tahun" name="tahun"></select>
                            </div>

                            <div class="form-group">
                                <label>Slug</label>
                                <small class="text-muted"><i>link yang akan diakses oleh peserta <?= $this->config->item('app_singkatan') ?> agar lebih mudah dikenali</i></small>
                                <input type="text" class="form-control validate[required]" id="slug" name="slug">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Semester</label>
                                <select class="form-select validate[required] vselect2" id="semester" name="semester"></select>
                            </div>
                            <div class="form-group">
                                <label>Jenis <?= $this->config->item('app_singkatan') ?></label>
                                <select class="form-select validate[required] vselect2" id="jenis" name="jenis"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Tema <?= $this->config->item('app_singkatan') ?></label>
                                <input type="text" class="form-control validate[required]" id="tema" name="tema">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Angkatan</label>
                                <input type="text" class="form-control validate[required]" id="angkatan" name="angkatan">
                                <p><small class="text-muted">misal : I(satu) atau IX (sembilan) dan seterusnya</small></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Tempat Pelaksanaan</label>
                                <input type="text" class="form-control validate[required]" id="tempat" name="tempat">
                            </div>
                        </div>
                    </div>
                    <h5>Surat Keputusan Pelaksanaan Kegiatan</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. SK</label>
                                <input type="text" name="no_sk" id="no_sk" class="form-control validate[required]" placeholder="No. SK">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal SK</label>
                                <input type="text" name="tgl_sk" id="tgl_sk" class="form-control validate[required] datepicker" placeholder="Tanggal SK">
                            </div>
                        </div>
                    </div>


                    <h5>Jadwal</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pendaftaran</label>
                                <input type="text" name="daftarmulai" id="daftarmulai" class="form-control validate[required] datepicker" placeholder="Mulai">
                                <label>sampai dengan</label>
                                <input type="text" name="daftarselesai" id="daftarselesai" class="form-control validate[required] datepicker" placeholder="Selesai">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pelaksanaan <?= $this->config->item('app_singkatan') ?></label>
                                <input type="text" name="kknmulai" id="kknmulai" class="form-control validate[required] datepicker" placeholder="Mulai">
                                <label>sampai dengan</label>
                                <input type="text" name="kknselesai" id="kknselesai" class="form-control validate[required] datepicker" placeholder="Selesai">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Publikasi Kelompok</label>
                                <input type="text" name="bagikelompok" id="bagikelompok" class="form-control validate[required] datepicker" placeholder="Publikasi">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tugas Akhir</label>
                                <input type="text" name="tamulai" id="tamulai" class="form-control validate[required] datepicker" placeholder="Mulai">
                                <label>sampai dengan</label>
                                <input type="text" name="taselesai" id="taselesai" class="form-control validate[required] datepicker" placeholder="Selesai">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Penilaian</label>
                                <input type="text" name="nilaimulai" id="nilaimulai" class="form-control validate[required] datepicker" placeholder="Mulai">
                                <label>sampai dengan</label>
                                <input type="text" name="nilaiselesai" id="nilaiselesai" class="form-control validate[required] datepicker" placeholder="Selesai">
                            </div>
                        </div>

                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control editorweb" id="keterangan" name="keterangan" rows="6"></textarea>
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