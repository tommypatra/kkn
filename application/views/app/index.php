<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard <?= $this->config->item("app_name") ?></title>
    <link href="<?= base_url('assets/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/main/app.css">
    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/main/app-dark.css">

    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/shared/iconly.css">
    <?php include(APPPATH . "views/importhead.php"); ?>
</head>

<body>
    <div class="mloading"></div>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="<?= base_url() ?>"><?= $this->config->item("app_name") ?></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <?php
                    $idgrup =  $this->session->userdata('role');
                    include(APPPATH . "views/app/menu_header.php");
                    if ($idgrup == 1)
                        include(APPPATH . "views/app/menu_admin.php");
                    elseif ($idgrup == 4)
                        include(APPPATH . "views/app/menu_mahasiswa.php");
                    elseif ($idgrup == 5)
                        include(APPPATH . "views/app/menu_pembimbing.php");
                    include(APPPATH . "views/app/menu_footer.php");
                    ?>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>


            <!-- start content dashboard -->
            <?php $this->load->view($web['loadview']); ?>
            <!-- end content dashboard -->

            <?php include(APPPATH . "views/footer.php"); ?>
        </div>
    </div>


    <!-- Modal web -->
    <div class="modal fade text-left w-100" id="modal-login-sebagai" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
        <div class="modal-dialog modal-default" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalForm">Pilihan Menu Akses</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form id="form-login-sebagai">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" id="grup-login-sebagai">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- full size modal-->


    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/') ?>jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('templates/mazer/') ?>assets/js/app.js"></script>

    <script>
        //menampilkan form modal
        loadgruplogin();

        function loadgruplogin() {
            $("#grup-login-sebagai").html("");
            $.ajax({
                url: "<?= base_url("app/dashboard/loadgrup") ?>",
                timeout: 10000,
                type: "post",
                dataType: "json",
                success: function(vRet) {
                    $("#grup-login-sebagai").html(vRet.html);
                },
                error: function(request, status, error) {
                    if (request.responseText == "undefined")
                        alert("Terjadi kesalahan, periksa koneksi internet anda dan coba lagi");
                    else
                        alert(request.responseText);
                },
            });
        }

        $("#login-sebagai").click(function() {
            var myModal = new bootstrap.Modal(document.getElementById('modal-login-sebagai'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
        });
    </script>

    <?php include(APPPATH . "views/importfooter.php"); ?>
</body>

</html>