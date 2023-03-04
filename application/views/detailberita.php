<div class="page-heading">
    <h3>Berita <?= $this->config->item('app_singkatan') ?></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <?php
                            $dp = $detailberita;
                            $detail = strip_tags($dp['detail']);
                            $berita = explode(" ", $detail);
                            $urlberita = base_url("web/detailberita/" . $dp['idberita']);
                            ?>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><i class="bi bi-person-circle"></i> <?= $dp['nama'] ?> <span style="font-size:11px">publish : <?= $dp['waktu'] ?></span></li>
                            </ul>
                            <a href="<?= $urlberita ?>">
                                <?php
                                if ($dp['thumbnail']) {
                                    echo "<img src='" . base_url($dp['thumbnail']) . "' class='card-img-top img-fluid' >";
                                }
                                ?>
                            </a>
                            <span class="badge bg-secondary" style='font-size:11px;'><i class="bi bi-clock"></i> <?= waktu_lalu($dp['waktu']) ?></span>
                            <h5 class="card-title"><a href="<?= $urlberita ?>"><?= $dp['judul'] ?></a></h5>
                            <p class="card-text">
                                <?= $dp['detail']; ?>
                            </p>


                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>