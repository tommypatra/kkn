<div class="page-heading">
    <h3>
        <?= $this->config->item('app_singkatan') ?> - Dashboard <?= $pesertakkn['nama'] ?>
    </h3>

    <input type="hidden" id="iduser" name="iduser" value="<?= $pesertakkn['iduser'] ?>">
    <input type="hidden" id="iduser_session" name="iduser_session" value="<?= $this->session->userdata("iduser") ?>">
    <input type="hidden" id="idkkn" name="idkkn" value="<?= $pesertakkn['idkkn'] ?>">
    <input type="hidden" id="idpenempatan" name="idpenempatan" value="<?= $pesertakkn['idpenempatan'] ?>">
    <input type="hidden" id="idkelompok" name="idkelompok" value="<?= $pesertakkn['idkelompok'] ?>">
    <?php
    $ispeserta = false;
    if ($pesertakkn['iduser'] == $this->session->userdata("iduser"))
        $ispeserta = true;

    $phone = $pesertakkn['hp'];
    $email = $pesertakkn['email'];
    $kelemail = $kelompok['email'];
    if (!$this->session->userdata('iduser')) {
        $phone = preg_replace('/(\d{3})\d{4}(\d{3})/', '$1xxxx$2', $phone);
        $kelemail = preg_replace('/(?<=.)[^@](?=[^@]*?@)|(?:(?<=@.)|(?!^)\G(?=[^@]*$)).(?=.*\.)/', 'x', $kelemail);
        $email = preg_replace('/(?<=.)[^@](?=[^@]*?@)|(?:(?<=@.)|(?!^)\G(?=[^@]*$)).(?=.*\.)/', 'x', $email);
    }
    ?>


</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Kegiatan <?= $pesertakkn['tema'] ?> Tahun <?= $pesertakkn['tahun'] ?> (<?= $pesertakkn['jenis'] ?>)
                                <?php if ($ispeserta) { ?>
                                    <a href="#" class="text-gray-600 show show-notif" style="font-size:17px;">
                                        <i class="bi bi-bell"></i>
                                        <span class="jum-notif" style="font-size:13px"><?= $jumnotif ?></span>
                                    </a>
                                <?php } ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div>Tempat : <?= $pesertakkn['tempat'] ?></div>
                            <?php
                            $statuspelaksanaan = labeltanggal($pesertakkn['kknmulai'], $pesertakkn['kknselesai']);
                            //print_r($statuspelaksanaan);
                            ?>
                            <div>Waktu Pelaksanaan : <?= $pesertakkn['kknmulai'] . " sd " . $pesertakkn['kknselesai'] . " " . $statuspelaksanaan['labelbadge'] ?></div>
                            <div>Total Kegiatan : <span class="badge bg-secondary"><?= (isset($rekapaktifitas['kegtotal']) ? format_rupiah($rekapaktifitas['kegtotal']) : 0) ?></span>
                            </div>
                            <div>Total Estimasi Biaya : <span class="badge bg-secondary">Rp. <?= (isset($rekapaktifitas['esttotal']) ? format_rupiah($rekapaktifitas['esttotal']) : 0) ?></span>
                            </div>
                            <div>Jumlah Video Testimoni : <span class="badge bg-secondary" id="testimoni">0</span></div>


                            <div class="buttons mt-3">
                                <!--
                                <a href="#" class="btn btn-primary rounded-pill act-aktifitas" data-kategori="trending"><i class="bi bi-star"></i> Aktifitas Trending</a>
                                -->
                                <a href="<?= base_url('dashboard/kkn/' . $pesertakkn['idkkn']) ?>" class="btn btn-success rounded-pill"><i class="bi bi-house"></i> Dashboard <?= $this->config->item('app_singkatan') ?></a>
                                <a href="#" class="btn btn-info rounded-pill act-aktifitas" data-kategori="best"><i class="bi bi-graph-up-arrow"></i> Aktifitas Terbaik</a>
                                <?php if ($ispeserta) { ?>
                                    <a href="#" class="btn btn-danger rounded-pill aktifitas-dpl" data-idkelompok="<?= $pesertakkn['idkelompok'] ?>"><i class="bi bi-exclamation-octagon"></i> Aktifitas DPL <span style='font-weight:bold;' class="jum-notif-dpl">0</span></a>
                                    <a href="#" class="btn btn-secondary rounded-pill profil-posko" data-idkelompok="<?= $pesertakkn['idkelompok'] ?>"><i class="bi bi-postcard"></i> Profil Posko</a>
                                <?php } ?>
                                <a href="<?= base_url('web/kuesioner/' . $pesertakkn['idkkn']) ?>" class="btn btn-primary rounded-pill"><i class="bi bi-ui-checks"></i> Kuesioner</a>
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
                                        <div style="font-weight:bold;font-size:13px;"><?= $namakelompok ?></div>
                                    </a>
                                    <div style="font-size:12px;"><?= $pesertakkn['prodi'] ?></div>
                                    <div style="font-size:12px;"><i>Fakultas <?= $pesertakkn['fakultas'] ?></i></div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if ($namakelompok != "") {
                    ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Profil <?= $namakelompok ?></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h6>DPL : <?= $kelompok['nama'] ?></h6>
                                        <span style="font-size:12px;"><?= $kelemail ?></span>
                                        <div class="text-muted mb-0" style="font-size:12px">
                                            <span class="badge bg-success"><i class="bi bi-clock"></i> <?= waktu_lalu($kelompok['lastlogin']) ?></span>
                                        </div>

                                        <div class="align-items-center">
                                            Kelompok :
                                            <?= $kelompok['namakelompok'] ?>
                                        </div>
                                        <div class="align-items-center">
                                            Jumlah Personel : <span class="badge bg-primary"><?= count($anggota) ?></span>
                                        </div>
                                        <hr>
                                        <h6>Lokasi <?= $this->config->item('app_singkatan') ?> : </h6>
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

                </div>

                <div class="col-12 col-xl-8">
                    <?php if (count($is_pesertakkn) > 0 && $statuspelaksanaan['label'] == "Terbuka") {
                        $url = base_url("dashboard/personal/" . $is_pesertakkn['idpenempatan']);
                    ?>
                        <div class="card">
                            <form id="kegiatankkn">
                                <input type="hidden" id="idaktifitas" name="idaktifitas">
                                <div class="card-body">
                                    <div class="row" style="margin-bottom: 3px;">
                                        <div class="col-md-2">
                                            <a href="<?= $url ?>">
                                                <div class="avatar avatar-lg">
                                                    <img src="<?= base_url($is_pesertakkn['profilpic']) ?>">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-10">
                                            <h4>Kegiatan <?= $this->config->item('app_singkatan') ?></h4>
                                            Hi <?= $is_pesertakkn['nama'] ?>, yuk tuliskan kegiatanmu saat ini...
                                            <div class="row" style="font-size:12px">
                                                <div class="col-6">
                                                    <i class="bi bi-clock"></i> <input type="text" id="waktu" name="waktu" class="datepicker myclear-input" value="<?= date("Y-m-d H:i:s") ?>">
                                                </div>
                                                <div class="col-3">
                                                    <i class="bi bi-people"></i> <input style="width:50px" type="number" id="jummhs" name="jummhs" class="myclear-input" value="0" title="jumlah mahasiswa yg terlibat">
                                                </div>
                                                <div class="col-3">
                                                    <i class="bi bi-people-fill"></i> <input style="width:50px" type="number" id="jummasyarakat" name="jummasyarakat" class="myclear-input" value="0" title="jumlah masyarakat yg terlibat">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div id="editor" style="min-height: 50px;"></div>
                                    <div class="row" style="margin-top: 5px;">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control validate[required] appcurrency" id="estimasi" name="estimasi" title="estimasi biaya" placeholder="Estimasi Biaya" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-select validate[required]" id="grup" name="grup" title="jenis kegiatan">
                                                    <option value="Fisik">Fisik</option>
                                                    <option value="Non Fisik">Non Fisik</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary shadow-lg mt-2">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>

                    <div class="card" id="card_testimoni" style="display: none;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 id="reload_testimoni">Testimoni</h5>
                                    <div id="daftar_testimoni"></div>
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

            <?php if (count($pesertakkn) > 0) {
                $url = base_url("dashboard/personal/" . $pesertakkn['idpenempatan']);
                $url_kel = base_url("dashboard/kelompok/" . $pesertakkn['idkelompok']);
            ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Profil Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div style='font-weight:bold'><?= $pesertakkn['nama'] ?></div>
                                <div style="font-size:12px;"><?= $email ?></div>
                                <div class="align-items-center">
                                    Tempat/Tanggal Lahir :
                                    <?= ($pesertakkn['tmplahir'] != "") ? $pesertakkn['tmplahir'] . ", " : ""; ?>
                                    <?= $pesertakkn['tgllahir'] ?>
                                </div>
                                <div class="align-items-center">
                                    Jenis Kelamin : <span class="badge bg-primary"><?= $pesertakkn['kel'] ?></span>
                                </div>
                                <div class="align-items-center">
                                    HP : <span class="badge bg-primary"><?= $phone ?></span>
                                </div>
                                <div class="align-items-center">
                                    Alamat : <?= $pesertakkn['alamat'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

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
<div class="modal fade text-left w-100" id="modal-notif" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="bi bi-bell"></i> Pemberitahuan</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i> x
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="detail-notif"></div>
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

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-profil-posko" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Form Profil Posko</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i> x
                </button>
            </div>
            <form id="form-profil-posko">
                <input type="hidden" id="idposko" name="idposko">
                <div class="modal-body">
                    <div class="row">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Program Kerja Kelompok</label>
                                    <div class="form-control" id="proker" name="proker"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alamat Posko</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Foto Posko <i>(tampak depan)</i></label>
                                <input style='font-size:10px' type="file" id="path" name="path" accept="image/*">
                                <div id="imgpreview"></div>
                                <div style="font-size:12px;font-style:italic;">
                                    <b>Syarat Foto yand diupload :</b>
                                    <ul>
                                        <li>Tampak depan rumah yang menjadi posko;</li>
                                        <li>Saat mengambil gambar/foto pastikan GPS pada HP Aktif;</li>
                                        <li>Pada pengaturan kamera di HP aktifkan Location tags/ Penanda lokasi untuk mendetaksi lokasi posko pada peta secara otomatis</li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="map" style="height:250px;"></div>
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