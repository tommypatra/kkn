<!--
<div class="auth-logo">
    <a href="index.html"><img src="<?= base_url('templates/mazer/') ?>assets/images/logo/logo.svg" alt="Logo"></a>
</div>
-->
<h1 class="auth-title">Masuk.</h1>
<p class="auth-subtitle mb-5">Masuk ke system menggunakan data yang telah terdaftar.</p>

<form id="formlogin">
    <div class="form-group position-relative has-icon-left mb-4">
        <input type="text" class="form-control form-control-xl validate[required,custom[email]]" id="fldUser" name="fldUser" placeholder="Email">
        <div class="form-control-icon">
            <i class="bi bi-envelope"></i>
        </div>
    </div>
    <div class="form-group position-relative has-icon-left mb-4">
        <input type="password" class="form-control form-control-xl validate[required,minSize[8]]" id="fldPass" name="fldPass" placeholder="Password">
        <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
    </div>
    <div class="form-group position-relative has-icon-left mb-4">
        <input type="number" class="form-control form-control-xl validate[required]" id="fldHitung" name="fldHitung" placeholder="Hitung <?= $calculate['v1'] . '+' . $calculate['v2'] ?>">
        <div class="form-control-icon">
            <i class="bi bi-calculator"></i>
        </div>
    </div>
    <div class="form-check form-check-lg d-flex align-items-end">
        <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label text-gray-600" for="flexCheckDefault">
            Tetap masuk
        </label>
    </div>
    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" id="login">Log in</button>
</form>
<div class="text-center mt-5 text-lg fs-4">
    <p class="text-gray-600">Belum memiliki akun? <a href="<?= base_url("daftar") ?>" class="font-bold">Daftar</a>.</p>
    <p><a class="font-bold btn-lupapass" href="#">Lupa Password?</a>.</p>
    <p class="text-gray-600"><a href="<?= base_url() ?>" class="font-bold">Website <?= $this->config->item('app_singkatan') ?></a>.</p>

</div>

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-form-lupapass" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalForm">Lupa Password</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="formlupapass">
                <div class="modal-body">


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control validate[required,custom[email]]" id="email_lupa" name="email_lupa" placeholder="email terdaftar anda @iainpare.ac.id">
                                <ul class="mt-3" style="font-size:12px">
                                    <li>Link reset password hanya berlaku 30 menit sejak dibuat</li>
                                    <li>Pembuatan link reset password dalam 1 hari hanya bisa dilakukan maksimal 3 kali, selebihnya lakukan pada hari berikutnya</li>
                                    <li>Setelah berhasil mengirim link reset password, mohon lanjutkan reset password melalui email yang dapat di cek pada inbox atau spam email anda.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary ml-1">
                        Kirim Link Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- full size modal-->