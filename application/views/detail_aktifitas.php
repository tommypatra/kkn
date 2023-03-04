<div class="page-heading">
    <h3><?= $this->config->item('app_singkatan') ?> - Detail Aktifitas </h3>
    <input type="hidden" id="idaktifitas" name="idaktifitas" value="<?= $idaktifitas ?>">
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div id="daftarlkh"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
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