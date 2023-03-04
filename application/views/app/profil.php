<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3><?= $web['title'] ?></h3>
            <p class="text-subtitle text-muted">Pengaturan <?= $web['title'] ?> pada sistem terintegrasi</p>
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
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i> <?= $web['title'] ?>
            </h4>
            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>

        </div>

        <div class="card-body">

            <form id="formprofil">
                <input type="hidden" id="idprofil" name="idprofil">
                <input type="hidden" id="idjenis_profil" name="idjenis_profil" value="<?= $profil['id'] ?>">
                <div class="modal-body">



                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-control" id="editorberita" name="berita"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Thumbnail</label>
                                <input style='font-size:10px' type="file" id="path" name="path" accept="image/*">
                                <div id="imgpreview"></div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ml-1">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

</section>