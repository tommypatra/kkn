<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->config->item("app_name") ?></title>
    <link rel="stylesheet" href="<?= base_url('templates/mazer/assets/css/main/app.css?' . date('ymdhis')) ?>">

    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/shared/iconly.css">

    <?php include(APPPATH . "views/importhead.php"); ?>

</head>

<body>
    <div class="mloading"></div>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <div class="header-top">
                    <div class="container">
                        <div class="logo">
                            <h5><a href="<?= base_url() ?>"><?= $this->config->item("app_name") ?></a></h5>
                        </div>
                        <div class="header-top-right">

                            <?php if ($this->session->userdata('iduser')) { ?>
                                <div class="dropdown">

                                    <a href="<?= base_url("akun") ?>" class="user-dropdown d-flex dropend" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="avatar avatar-md2">
                                            <img src="<?= $this->session->userdata('profilpic') ?>" alt="Avatar">
                                        </div>
                                        <div class="text">
                                            <h6 class="user-dropdown-name"><?= $this->session->userdata('nama') ?></h6>
                                            <p class="user-dropdown-status text-sm text-muted">Member
                                            </p>

                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="<?= base_url('akun') ?>">My Account</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="<?= base_url("logout") ?>">Logout</a></li>
                                    </ul>
                                </div>
                            <?php } ?>

                            <!-- Burger button responsive -->
                            <a href="#" class="burger-btn d-block d-xl-none">
                                <i class="bi bi-justify fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php include("menuweb.php") ?>
            </header>

            <div class="content-wrapper container">

                <!-- start content dashboard -->
                <?php $this->load->view($web['loadview']); ?>
                <!-- end content dashboard -->

            </div>

            <?php include(APPPATH . "views/footer.php"); ?>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/') ?>jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/boostrap.min.js') ?>"></script>

    <script src="<?= base_url('templates/mazer/') ?>assets/js/pages/horizontal-layout.js"></script>

    <?php include(APPPATH . "views/importfooter.php"); ?>

</body>

</html>