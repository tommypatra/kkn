<section class="section">
    <input type="hidden" id="idkkn" name="idkkn" value="<?= $idkkn ?>">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title d-flex">
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>
                DAFTAR PESERTA KPM
                <?= $datakkn['tema'] . " (" . $datakkn['jenis'] . ")" ?>
            </h4>

            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <a href="<?= base_url('dashboard/kkn/' . $idkkn) ?>" class="btn btn-primary rounded-pill"><i class="bi bi-stack"></i> Dashboard KPM</a>
                <i class="bi bi-pin-map-fill"></i> <?= $datakkn['tempat'] ?>

                <table class="table table-striped table-sm table-data">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Mahasiswa</th>
                            <th style="vertical-align: middle;" scope="col">Jenis Kelamin</th>
                            <th style="vertical-align: middle;" scope="col">Kelompok</th>
                            <th style="vertical-align: middle;" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</section>