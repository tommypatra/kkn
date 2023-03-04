<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $web['title'] ?></h3>
            <p class="text-subtitle text-muted"><?= $web['title'] ?> pada sistem terintegrasi</p>
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
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Tabel <?= $web['title'] ?>
            </h4>
            <div class="list-inline d-flex">
                <div><select class="form-select" id="tahun" name="tahun"></select> </div>&nbsp;
                <div class="buttons">
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-jadwal">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Pengabdian</th>
                            <th style="vertical-align: middle;" scope="col">Kelompok</th>
                            <th style="vertical-align: middle;" scope="col">Data Mahasiswa</th>
                            <th style="vertical-align: middle;" scope="col">Prodi</th>
                            <th style="vertical-align: middle;" scope="col">Nilai</th>
                            <th style="vertical-align: middle;" scope="col">Nilai Pembimbing</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</section>