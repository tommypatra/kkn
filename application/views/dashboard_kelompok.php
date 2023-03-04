<div class="page-heading">
    <h3><?= $this->config->item('app_singkatan') ?> - Dashboard Kelompok <?= $kelompok['namakelompok'] ?></h3>
    <input type="hidden" id="idkelompok" name="idkelompok" value="<?= $kelompok['idkelompok'] ?>">
    <input type="hidden" id="idkkn" name="idkkn" value="<?= $kelompok['idkkn'] ?>">
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Kegiatan <?= $pesertakkn['tema'] ?> Tahun <?= $pesertakkn['tahun'] ?> (<?= $pesertakkn['jenis'] ?>)</h5>
                        </div>
                        <div class="card-body">
                            <div>Tempat : <?= $pesertakkn['tempat'] ?></div>
                            <div>Waktu Pelaksanaan : <?= $pesertakkn['kknmulai'] . " sd " . $pesertakkn['kknselesai'] . " " . labeltanggal($pesertakkn['kknmulai'], $pesertakkn['kknselesai'])['labelbadge'] ?></div>
                            <div>Total Kegiatan : <span class="badge bg-secondary"><?= (isset($rekapaktifitas['kegtotal']) ? format_rupiah($rekapaktifitas['kegtotal']) : 0) ?></span>
                            </div>
                            <div>Total Estimasi Biaya : <span class="badge bg-secondary">Rp. <?= (isset($rekapaktifitas['esttotal']) ? format_rupiah($rekapaktifitas['esttotal']) : 0) ?></span>
                            </div>

                            <div class="buttons mt-3">
                                <!--
                                <a href="#" class="btn btn-primary rounded-pill act-aktifitas" data-kategori="trending"><i class="bi bi-star"></i> Aktifitas Trending</a>
                                -->
                                <a href="#" class="btn btn-info rounded-pill act-aktifitas" data-kategori="best"><i class="bi bi-graph-up-arrow"></i> Aktifitas Terbaik</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-xl-4">

                    <?php
                    $namakelompok = ($pesertakkn['ketpublishkelompok'] == "terbuka") ? "Kelompok " . $kelompok['namakelompok'] : null;
                    if (count($pesertakkn) > 0) {
                        $url = base_url("dashboard/personal/" . $pesertakkn['idpenempatan']);
                        $url_kel = base_url("dashboard/kelompok/" . $pesertakkn['idkelompok']);
                    }
                    if ($namakelompok != "") {
                    ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Profil <?= $namakelompok ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="align-items-center">
                                            Jumlah Personel : <span class="badge bg-primary"><?= count($anggota) ?></span>
                                        </div>
                                        <hr>
                                        <h6>Lokasi KPM : </h6>
                                        <div style="font-size:13px">
                                            <div class="align-items-center">
                                                <?= $kelompok['provinsi'] ?>
                                            </div>
                                            <div class="align-items-center">
                                                <?= $kelompok['kabupaten'] ?>
                                            </div>
                                            <div class="align-items-center">
                                                <?= $kelompok['kecamatan'] ?>
                                            </div>
                                            <div class="align-items-center">
                                                Kelurahan/ Desa : <?= $kelompok['desa'] ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="card">
                        <div class="card-header">
                            <h4>Posko</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <?= ($kelompok['fotoposko'] != "") ? "<img src='" . base_url($kelompok['fotoposko']) . "' width='100%'>" : "belum ada" ?>
                                    <?php
                                    if ($kelompok['latitude'] && $kelompok['longitude'])
                                        echo "<a href='https://www.google.com/maps/search/?api=1&query=" . $kelompok['latitude'] . "," . $kelompok['longitude'] . "' target='_blank'><i class='bi bi-map'></i> Lokasi Google Map</a>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Peta Lokasi Kelompok</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12" id="map" data-latitude="<?= $kelompok['latitude'] ?>" data-longitude="<?= $kelompok['longitude'] ?>" style="height:250px;">
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
                        <div class="col-sm-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading"><i class="bi bi-cash-stack"></i> Estimasi Biaya Fisik</h6>
                                <p>Rp. <?= (isset($rekapaktifitas['estbiayafisik']) ? format_rupiah($rekapaktifitas['estbiayafisik']) : 0) ?></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="bi bi-wallet2"></i> Estimasi Biaya Non Fisik</h6>
                                <p>Rp. <?= (isset($rekapaktifitas['estbiayanonfisik']) ? format_rupiah($rekapaktifitas['estbiayanonfisik']) : 0) ?></p>
                            </div>
                        </div>
                    </div>

                    <!--
                    <div class="card">
                        <div class="card-header">
                            <h4>Rekap Kegiatan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="align-items-center">
                                        Total Kegiatan : <span class="badge bg-primary"><?= (isset($rekapaktifitas['kegtotal']) ? $rekapaktifitas['kegtotal'] : 0) ?></span>
                                    </div>
                                    <div class="align-items-center">
                                        Fisik : <span class="badge bg-primary"><?= (isset($rekapaktifitas['kegfisik']) ? $rekapaktifitas['kegfisik'] : 0) ?></span>
                                    </div>
                                    <div class="align-items-center">
                                        Non Fisik : <span class="badge bg-primary"><?= (isset($rekapaktifitas['kegnonfisik']) ? $rekapaktifitas['kegnonfisik'] : 0) ?></span>
                                    </div>
                                    <div class="align-items-center">
                                        Estimasi Biaya Fisik : <span class="badge bg-primary">Rp. <?= (isset($rekapaktifitas['estbiayafisik']) ? format_rupiah($rekapaktifitas['estbiayafisik']) : 0) ?></span>
                                    </div>
                                    <div class="align-items-center">
                                        Estimasi Biaya Non Fisik : <span class="badge bg-primary">Rp. <?= (isset($rekapaktifitas['estbiayanonfisik']) ? format_rupiah($rekapaktifitas['estbiayanonfisik']) : 0) ?></span>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                    -->

                </div>

                <div class="col-12 col-xl-8">


                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5>Program Kerja</h5>
                                    <?= ($kelompok['proker'] != "") ? $kelompok['proker'] : "belum ada" ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="daftarlkh"></div>
                    <div class="btn btn-primary shadow-lg mb-4" id="loadMoreLKH">Tampilkan Kegiatan Lainnya</div>
                </div>

            </div>
        </div>
        <div class="col-12 col-lg-3">

            <div class="card" style="vertical-align:top; text-align:center">
                <div class="card-header">
                    <h4>Dosen Pembimbing Lapangan</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <img src="<?= base_url($kelompok['profilpic']) ?>" height="130px">
                            <div style='font-weight:bold'><?= $kelompok['nama'] ?></div>
                            <div style="font-size:12px;"><?= $kelompok['email'] ?></div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-header">
                    <h4>Anggota <?= $namakelompok ?></h4>
                </div>
                <div class="card-content pb-4">
                    <?php if (count($anggota) > 0 && $pesertakkn['ketpublishkelompok'] == "terbuka") {
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
                                    <a href="<?= $url_kel ?>">
                                        <div>Kelompok <?= $da['namakelompok'] ?></div>
                                    </a>
                                    <h6 class="text-muted mb-0"><?= $da['jabatan'] ?></h6>
                                    <div style='font-size:11px'><?= "NIM. " . $da['nim'] ?></div>
                                    <div style='font-size:11px'><?= $da['prodi'] ?></div>
                                    <div style='font-size:11px'><span class="badge bg-success"><?= waktu_lalu($da['lastlogin']) ?></span></div>
                                </div>
                            </div>
                        <?php
                        }
                    } else { ?>
                        <div class="px-4">
                            Belum Tersedia
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