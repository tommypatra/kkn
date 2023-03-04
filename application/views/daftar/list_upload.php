<section class="section">
    <input type="hidden" id="dokumen" name="dokumen" value="<?= $dokumen ?>">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title d-flex">
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>
                <?= $web['title'] ?>
            </h4>

            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-sm table-data">
                    <thead style='display:none;'>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Dokumen</th>
                            <th style="vertical-align: middle;" scope="col">Download</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

</section>