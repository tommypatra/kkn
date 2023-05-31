<?php
$tmpImport = isset($web['importPlugins']) ? $web['importPlugins'] : array();
if (count($tmpImport) > 0) {
    foreach ($tmpImport as $tmpPlugins) {
        if (!is_array($tmpPlugins['css']))
            echo ($tmpPlugins['css'] != "") ? "<link rel='stylesheet' href='" . $tmpPlugins['css'] . "'>\n" : "";
        elseif (count($tmpPlugins['css']) > 1) {
            foreach ($tmpPlugins['css'] as $tmpPluginsExt)
                echo ($tmpPluginsExt != "") ? "<link rel='stylesheet' href='" . $tmpPluginsExt . "'>\n" : "";
        }
    }
}

$importCss = isset($web['importCss']) ? $web['importCss'] : array();
if (count($importCss) > 0) {
    foreach ($importCss as $import)
        if (filter_var($import, FILTER_VALIDATE_URL)) {
            echo "<script src='" . $import . "'></script>\n";
        } else {
            $this->load->view($import);
        }
}
?>

<meta property="og:type" content="article" />
<meta property="og:site_name" content="KPM IAIN Parepare" />
<meta property="og:title" content="Aplikasi KPM IAIN Parepare" />
<meta property="og:image" content="<?= base_url("assets/img/logoapp.png") ?>" />
<meta property="og:description" content="Halaman Utama Website KPM Institut Agama Islam Negeri Parepare (IAIN Parepare) yang digunakan sebuah website terintegrasi dalam pelaksanaan KPM di IAIN Parepare." />
<meta property="og:url" content="<?= base_url() ?>" />
<meta property="og:image:type" content="image/jpeg" />
<meta property="og:image:width" content="650" />
<meta property="og:image:height" content="366" />

<meta name="copyright" content="Halaman Utama Website KPM Institut Agama Islam Negeri Parepare (IAIN Parepare) yang digunakan sebuah website terintegrasi dalam pelaksanaan KPM di IAIN Parepare." itemprop="dateline" />
<meta name="robots" content="index, follow" />
<meta name="author" content="admin" />
<meta name="description" content="Halaman Utama Website KPM Institut Agama Islam Negeri Parepare (IAIN Parepare) yang digunakan sebuah website terintegrasi dalam pelaksanaan KPM di IAIN Parepare." itemprop="description" />
<meta content="Halaman Utama Website KPM Institut Agama Islam Negeri Parepare (IAIN Parepare) yang digunakan sebuah website terintegrasi dalam pelaksanaan KPM di IAIN Parepare." itemprop="headline" />
<meta name="keywords" content="Halaman Utama Website KPM IAIN Parepare" itemprop="keywords" />
<meta name="thumbnailUrl" content="<?= base_url("assets/img/logoapp.png") ?>" itemprop="thumbnailUrl" />
<meta property="article:author" content="https://www.facebook.com/iainparepare/" itemprop="author" />
<meta property="article:publisher" content="https://www.facebook.com/iainparepare/" />
<meta content="<?= base_url() ?>" itemprop="url" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@iainpare" />
<meta name="twitter:site:id" content="@iainpare" />
<meta name="twitter:creator" content="@iainpare" />
<meta name="twitter:description" content="Halaman Utama Website KPM Institut Agama Islam Negeri Parepare (IAIN Parepare) yang digunakan sebuah website terintegrasi dalam pelaksanaan KPM di IAIN Parepare." />
<meta name="twitter:image:src" content="<?= base_url("assets/img/logoapp.png") ?>" />

<script>
    var vBase_url = "<?= base_url() ?>";
    var vCurrent_url = "<?= current_url() ?>";
    var vMaxSize = <?= $this->config->item('max_size') ?>;
    var vMaxSizeImg = <?= $this->config->item('max_size_img') ?>;
    var vTahunApp = <?= $this->config->item('tahunapp') ?>;
    var vTimeout = <?= $this->config->item('ajax_timeout') ?>;
    var vLimitRows = <?= $this->config->item('limit_rows') ?>;
    var vDelay = <?= $this->config->item('delay') ?>;
    var vLatitude = <?= $this->config->item('app_latitude') ?>;
    var vLongitude = <?= $this->config->item('app_longitude') ?>;
</script>