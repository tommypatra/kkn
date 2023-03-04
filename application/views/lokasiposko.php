<div class="page-heading">
    <h3>Lokasi Posko <span id="tema"></span></h3>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="idkkn" name="idkkn" value="<?= $idkkn ?>">
                            <a href="<?= base_url("dashboard/kkn/" . $idkkn) ?>" class="btn btn-primary rounded-pill"><i class="bi bi-stack"></i> Dashboard KPM</a>
                            <hr>
                            <div id="map" style="width:100%;height:100vh;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>