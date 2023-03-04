<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->config->item('app_name') . " " . $this->config->item('app_version') ?>
        <?= isset($web['page']) ? " | " . $web['page'] : "" ?>
    </title>
    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/main/app.css">
    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/pages/auth.css">
    <link rel="shortcut icon" href="<?= base_url('templates/mazer/') ?>assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url('templates/mazer/') ?>assets/images/logo/favicon.png" type="image/png">
    <link href="<?= base_url('assets/bootstrap.min.css') ?>" rel="stylesheet">

    <?php include(APPPATH . "views/importhead.php"); ?>

</head>

<body>
    <div class="mloading"></div>
    <div id="auth">

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <?php $this->load->view($loadview); ?>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/') ?>jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/bootstrap.bundle.min.js') ?>"></script>

    <?php include(APPPATH . "views/importfooter.php"); ?>


</body>

</html>