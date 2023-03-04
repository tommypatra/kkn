<div class="page-heading">
    <h3>Profil <?= $this->config->item('app_singkatan') ?> - <?= $jenisprofil['jenis'] ?></h3>
</div>


<div class="page-content">
    <section class="row">
        <div class="col-12">

            <div class="row">
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-body">

                            <?php
                            $dp = $dataprofil[0];
                            $detail = strip_tags($dp['detail']);
                            ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="bi bi-person-circle"></i> <?= $dp['nama'] ?> <span style="font-size:11px">publish : <?= $dp['waktu'] ?></span></li>
                            </ul>
                            <?php
                            if ($dp['thumbnail']) {
                                echo "<img src='" . base_url($dp['thumbnail']) . "?time=" . date("hYimsd") . "' class='card-img-top img-fluid' >";
                            }
                            ?>
                            <span class="badge bg-secondary" style='font-size:11px;'><i class="bi bi-clock"></i> <?= waktu_lalu($dp['waktu']) ?></span>
                            <h5 class="card-title"><?= $jenisprofil['jenis'] ?></h5>
                            <p class="card-text">
                                <?= $dp['detail']; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profil Web</h4>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><a href="<?= base_url('web/profil/1') ?>">Visi Misi</a></li>
                                <li><a href="<?= base_url('web/profil/2') ?>">Tujuan</a></li>
                                <li><a href="<?= base_url('web/profil/3') ?>">Struktur Organisasi</a></li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>
</div>