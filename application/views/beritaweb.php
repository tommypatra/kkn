<div class="page-heading">
    <section class="row">
        <div class="col-6 col-lg-8">
            <h3>Daftar Berita <?= $this->config->item('app_singkatan') ?></h3>
        </div>
        <div class="col-6 col-lg-4">
            <input type="text" class="form-control" id="cari" placeholder="enter untuk mencari..." title="pencarian">
        </div>
    </section>
</div>
<div class="page-content">
    <section class="row" id="daftar"></section>
    <div class="btn btn-primary shadow-lg mb-4" id="loadMore">Tampilkan Rilis Lainnya</div>
</div>