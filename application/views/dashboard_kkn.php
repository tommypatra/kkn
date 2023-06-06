<div class="page-heading">
    <h3>Dashboard <?= $this->config->item('app_singkatan') ?></h3>
    <input type="hidden" name="idkkn" id="idkkn" value="<?= $datakkn['idkkn'] ?>">
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Kegiatan <?= $datakkn['tema'] ?> Tahun <?= $datakkn['tahun'] ?> (<?= $datakkn['jenis'] ?>)</h5>
                        </div>
                        <div class="card-body">
                            <div>Tempat : <?= $datakkn['tempat'] ?></div>
                            <div>Waktu Pelaksanaan : <?= $datakkn['kknmulai'] . " sd " . $datakkn['kknselesai'] . " " . labeltanggal($datakkn['kknmulai'], $datakkn['kknselesai'])['labelbadge'] ?></div>
                            <div>Total Kegiatan <span class="badge bg-secondary"><?= (isset($rekapaktifitas['kegtotal']) ? format_rupiah($rekapaktifitas['kegtotal']) : 0) ?></span></div>
                            <div>Total Estimasi Biaya Rp. <span class="badge bg-secondary"><?= (isset($rekapaktifitas['esttotal']) ? format_rupiah($rekapaktifitas['esttotal']) : 0) ?></span></div>
                            <hr>
                            <?= $datakkn['keterangan'] ?>

                            <div class="buttons mt-3">
                                <a href="<?= base_url('web/peserta/' . $datakkn['idkkn']) ?>" class="btn btn-primary rounded-pill"><i class="bi bi-person-check"></i> Peserta</a>
                                <a href="<?= base_url('web/dpl/' . $datakkn['idkkn']) ?>" class="btn btn-info rounded-pill"><i class="bi bi-person-workspace"></i> DPL <?= $this->config->item('app_singkatan') ?></a>
                                <a href="<?= base_url('web/lokasi/' . $datakkn['idkkn']) ?>" class="btn btn-secondary rounded-pill"><i class="bi bi-geo-alt"></i> Daftar Lokasi</a>
                                <a href="<?= base_url('web/lokasiposko/' . $datakkn['idkkn']) ?>" class="btn btn-danger rounded-pill"><i class="bi bi-geo-fill"></i> Lokasi Posko</a>
                                <a href="javascript:;" class="btn btn-success rounded-pill act-kelompok" data-kategori="teraktif"><i class="bi bi-arrow-up-right"></i> Kelompok Teraktif</a>
                                <!--
                                <a href="#" class="btn btn-primary rounded-pill act-aktifitas" data-kategori="trending"><i class="bi bi-star"></i> Aktifitas Trending</a>
                                -->
                                <a href="javascript:;" class="btn btn-info rounded-pill act-aktifitas" data-kategori="best"><i class="bi bi-graph-up-arrow"></i> Aktifitas Terbaik</a>
                                <a href="<?= base_url('web/kuesioner/' . $datakkn['idkkn']) ?>" class="btn btn-primary rounded-pill"><i class="bi bi-ui-checks"></i> Kuesioner</a>
                            </div>


                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="alert alert-primary">
                            <h6 class="alert-heading"><i class="bi bi-building"></i> Kegiatan Fisik</h6>
                            <p><?= (isset($rekapaktifitas['kegfisik']) ? format_rupiah($rekapaktifitas['kegfisik']) : 0) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="alert alert-success">
                            <h6 class="alert-heading"><i class="bi bi-file-earmark-bar-graph"></i> Kegiatan Non Fisik</h6>
                            <p><?= (isset($rekapaktifitas['kegnonfisik']) ? format_rupiah($rekapaktifitas['kegnonfisik']) : 0) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="bi bi-cash-stack"></i> Estimasi Biaya Fisik</h6>
                            <p>Rp. <?= (isset($rekapaktifitas['estbiayafisik']) ? format_rupiah($rekapaktifitas['estbiayafisik']) : 0) ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-wallet2"></i> Estimasi Biaya Non Fisik</h6>
                            <p>Rp. <?= (isset($rekapaktifitas['estbiayanonfisik']) ? format_rupiah($rekapaktifitas['estbiayanonfisik']) : 0) ?></p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-xl-12">
                    <div id="daftarlkh"></div>
                    <div class="btn btn-primary shadow-lg mb-4" id="loadMoreLKH">Tampilkan Kegiatan Lainnya</div>

                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">

            <div class="card">
                <div class="card-header">
                    <h4>Profil <?= $this->config->item('app_singkatan') ?></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div><i class="bi bi-person-check"></i> Total Peserta : <?= count($anggota) ?></div>
                            <div><i class="bi bi-person-workspace"></i> Total Pembimbing : <?= count($pembimbing) ?></div>
                            <div><i class="bi bi-collection"></i> Total Kelompok : <?= count($kelompok) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (count($pesertakkn) > 0) {
                $url = base_url("dashboard/personal/" . $pesertakkn['idpenempatan']);
                $url_kel = base_url("dashboard/kelompok/" . $pesertakkn['idkelompok']);
            ?>
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <a href="<?= $url ?>">
                                    <img src="<?= base_url($pesertakkn['profilpic']) ?>" alt="Face 1">
                                </a>
                            </div>
                            <div class="ms-3 name">
                                <a href="<?= $url ?>">
                                    <h5 class="font-bold"><?= $pesertakkn['nama'] ?></h5>
                                </a>
                            </div>
                        </div>
                        <div style="text-align:center;margin-top:5px;">
                            <div style="font-weight:bold;font-size:13px;">NIM <?= $pesertakkn['nim'] ?></div>
                            <a href="<?= $url_kel ?>">
                                <div style="font-weight:bold;font-size:13px;">Kelompok <?= $pesertakkn['namakelompok'] ?></div>
                            </a>
                            <div style="font-size:12px;"><?= $pesertakkn['prodi'] ?></div>
                            <div style="font-size:12px;"><i>Fakultas <?= $pesertakkn['fakultas'] ?></i></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="card">
                <div class="card-header">
                    <h4>DPL <?= $this->config->item('app_singkatan') ?></h4>
                </div>
                <div class="card-content pb-4">
                    <?php if (count($pembimbing) > 0) {
                        foreach ($pembimbing as $i => $da) {
                            $url = "#";
                    ?>
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg">
                                    <a href="<?= $url ?>">
                                        <img src="<?= base_url($da['profilpic']) ?>">
                                    </a>
                                </div>
                                <div class="name ms-4">
                                    <a href="<?= $url ?>">
                                        <h5 class="mb-1"><?= $da['nama'] ?></h5>
                                    </a>
                                    <div class="text-muted mb-0" style="font-size:12px">
                                        <span class="badge bg-success"><i class="bi bi-clock"></i> <?= waktu_lalu($da['lastlogin']) ?></span>
                                    </div>
                                </div>

                            </div>
                        <?php
                            if ($i >= 4) {
                                break;
                            }
                        }
                    } else { ?>
                        <div class="px-4">
                            Belum ada
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Peserta <?= $this->config->item('app_singkatan') ?></h4>
                </div>
                <div class="card-content pb-4">
                    <?php if (count($anggota) > 0) {
                        foreach ($anggota as $i => $da) {
                            $url = base_url("dashboard/personal/" . $da['idpenempatan']);
                            $url_kel = base_url("dashboard/kelompok/" . $da['idkelompok']);
                    ?>
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg">
                                    <a href="<?= $url ?>">
                                        <img src="<?= base_url($da['profilpic']) ?>">
                                    </a>
                                </div>
                                <div class="name ms-4">
                                    <a href="<?= $url ?>">
                                        <h5 class="mb-1"><?= $da['nama'] ?></h5>
                                    </a>
                                    <?php if ($da['namakelompok'] && $datakkn['ketpublishkelompok'] == "terbuka") { ?>
                                        <a href="<?= $url_kel ?>">
                                            <div>Kelompok <?= $da['namakelompok'] ?></div>
                                        </a>
                                        <h6 class="text-muted mb-0"><?= $da['jabatan'] ?></h6>
                                    <?php } ?>
                                    <div style='font-size:11px'><?= "NIM. " . $da['nim'] ?></div>
                                    <div style='font-size:11px'><?= $da['prodi'] ?></div>
                                    <div class="text-muted mb-0" style="font-size:12px">
                                        <span class="badge bg-success"><i class="bi bi-clock"></i> <?= waktu_lalu($da['lastlogin']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php
                            if ($i >= 4) {
                                break;
                            }
                        }
                    } else { ?>
                        <div class="px-4">
                            Belum ada
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>User Aktif</h4>
                </div>
                <div class="card-body">
                    <h3 id="jum-user-aktif"></h3>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h4>Pengunjung</h4>
                </div>
                <div class="card-body">
                    <div class="row" style="text-align:center">
                        <a href="https://info.flagcounter.com/AYeJ"><img src="https://s01.flagcounter.com/count2/AYeJ/bg_FFFFFF/txt_000000/border_CCCCCC/columns_2/maxflags_20/viewers_0/labels_1/pageviews_1/flags_0/percent_0/" alt="Flag Counter" border="0"></a>
                    </div>
                </div>
            </div>


        </div>
    </section>
</div>

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-aktifitas" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aktifitas</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i> x
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <i style="font-size:13px;">Tingkatkan Aktifitas dan Like pada kegiatan anda </i>
                    <div class="detail-aktifitas-populer"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!-- full size modal-->