<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="<?= base_url("templates/mazer/") ?>assets/css/main/app.css">
    <link rel="shortcut icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">
    <link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">

    <link rel="stylesheet" href="<?= base_url('templates/mazer/') ?>assets/css/shared/iconly.css">

    <?php include(APPPATH . "views/importhead.php"); ?>

</head>

<body>
    <div class="container">
        <div class="col-md-6 col-10 offset-md-3">
            <div class="text-center">
                <?php if ($token['status']) { ?>
                    <form id="formpass">
                        <input type="hidden" name="token" id="token" value="<?= $idtoken ?>">
                        <p class="fs-5 text-gray-600">
                            Password baru anda
                        </p>
                        <input type="password" class="form-control validate[required,minSize[8]]" name="pass1" id="pass1" placeholder="password baru">
                        <p class="fs-5 text-gray-600">
                            Ulangi Password baru
                        </p>
                        <input type="password" class="form-control validate[required,equals[pass1]]" name="pass2" id="pass2" placeholder="ulangi password baru">
                        <input type="submit" class="btn btn-primary btn-lg shadow-lg mt-3" value="Simpan Password Baru!">
                    </form>
                <?php } else { ?>
                    <p class="fs-5 text-gray-600 mt-5">
                        <?= $token['pesan'] ?>
                    </p>
                <?php } ?>
                <a href="<?= base_url() ?>" class="btn btn-lg btn-outline-primary mt-3">Kembali Ke Web</a>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/') ?>jquery/jquery.min.js"></script>
    <?php include(APPPATH . "views/importfooter.php"); ?>
</body>

</html>