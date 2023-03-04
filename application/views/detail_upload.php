<?php
$datamhs = $pesertakkn['db'][0];
$urlpersonal = base_url("dashboard/personal/" . $datamhs['idpenempatan']);
$profilpic = base_url($datamhs['profilpic']);
?>
<div class="page-heading">
    <section class="row">
        <div class="col-6 col-lg-8">
            <h3>Output <?= $this->config->item('app_singkatan') ?></h3>
            <h5><?= $datamhs['tema'] . " (" . $datamhs['tahun'] . ")" ?></h5>
        </div>
    </section>
</div>
<div class="page-content">

    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-xl">
                    <a href="<?= $urlpersonal ?>">
                        <img src="<?= $profilpic ?>" alt="Face 1">
                    </a>
                </div>
                <div class="ms-3 name">
                    <a href="<?= $urlpersonal ?>">
                        <h5 class="font-bold"><?= $datamhs['nama'] ?></h5>
                    </a>
                    <div style="font-weight:bold;font-size:13px;">NIM <?= $datamhs['nim'] ?></div>
                    <a href="<?= base_url("dashboard/kelompok/" . $datamhs['idkelompok']) ?>">
                        <div style="font-weight:bold;font-size:13px;">Kelompok 93</div>
                    </a>
                    <div style="font-size:12px;"><?= $datamhs['prodi'] ?> (<i>Fakultas <?= $datamhs['fakultas'] ?></i>)</div>
                    <div style="font-size:12px;"><i>Email : <?= $datamhs['email'] ?></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class='table table-striped table-hover'>
                <thead>
                    <tr>
                        <th style='width:5%;'>No</th>
                        <th style='width:65%;'>Jenis Output</th>
                        <th style='width:30%;'>Status Upload</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataoutput['db'] as $i => $dp) { ?>
                        <tr>
                            <td><?= ($i + 1) ?></td>
                            <td><?= $dp['output'] . "<br>" . $dp['keterangan'] ?></td>
                            <td>
                                <?php
                                //print_r($dp);
                                if ($dp['idoutput_penempatan']) {

                                    //sisa bedakan antara url dan file
                                    $url = "";
                                    if ($dp['jenis'] == "upload") {
                                        $url = base_url("file/read/" . $dp['idoutput_penempatan'] . "/output_penempatan");
                                        $fileinfo = json_decode($dp['fileinfo'], true);
                                        $ukuran = ($fileinfo['file_size'] / 1000);
                                        $iconfile = "bi bi-filetype-pdf";
                                    } else {
                                        $ukuran = 0;
                                        $url = $dp['path'];
                                        $parse = parse_url($url);
                                        //debug($parse);
                                        $fileinfo['client_name'] = "urlweb - " . $parse['host'];
                                        $iconfile = "bi bi-globe";
                                    }
                                    $lblukuran = "";
                                    if ($ukuran > 0) {
                                        $lblukuran = "<span style='font-size:12px;font-weight:bold;'>(" . number_format($ukuran, 2, '.', '') . "MB)</span>";
                                    }
                                    //print_r($fileinfo);
                                ?>
                                    <a href='<?= $url ?>' target='_blank'>
                                        <i class="<?= $iconfile ?>"></i> <?= $fileinfo['client_name'] ?>
                                    </a>
                                    <?= $lblukuran ?>
                                    <br>
                                    <div style='font-size:12px;mb-2'><span class="badge bg-success"><?= $dp['waktu_upload'] ?></span></div>
                                <?php
                                } else {
                                    echo "belum upload";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>