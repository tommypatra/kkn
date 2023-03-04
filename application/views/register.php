<!--
<div class="auth-logo">
    <a href="index.html"><img src="assets/images/logo/logo.svg" alt="Logo"></a>
</div>
-->
<input type="hidden" id="datagrup" value='<?= json_encode($selectgrup) ?>'>
<h1 class="auth-title">Pendaftaran</h1>
<p class="auth-subtitle mb-5">Silahkan mengisi formulir berikut untuk mendaftaran di website ini.</p>
<form id="formregister">
    <div class="form-group position-relative has-icon-left mb-4">
        <input type="text" class="form-control form-control-xl validate[required]" id="nama" name="nama" placeholder="Nama Tanpa Gelar">
        <div class="form-control-icon">
            <i class="bi bi-person"></i>
        </div>
    </div>

    <div class="form-group position-relative has-icon-left mb-4">
        <input type="text" class="form-control form-control-xl validate[required,custom[email]]" id="email" name="email" placeholder="Email @iainpare.ac.id">
        <div class="form-control-icon">
            <i class="bi bi-envelope"></i>
        </div>
    </div>

    <div class="form-group position-relative has-icon-left mb-4">
        <input type="number" class="form-control form-control-xl validate[required,minSize[10],maxSize[12]]" id="hp" name="hp" placeholder="Nomor HP">
        <div class="form-control-icon">
            <i class="bi bi-phone"></i>
        </div>
    </div>

    <div class="form-group position-relative has-icon-left mb-4">
        <input type="number" class="form-control form-control-xl validate[required,minSize[16],maxSize[16]]" id="nik" name="nik" placeholder="NIK (16 digit)">
        <div class="form-control-icon">
            <i class="bi bi-input-cursor"></i>
        </div>
    </div>

    <div class="form-group position-relative has-icon-left mb-4">
        <select name="idgrup" id="idgrup" class="form-control form-control-xl select_grup validate[required]">
            <option value="">- Pilih Status-</option>
            <?php foreach ($selectgrup as $i => $dp) { ?>
                <option value="<?= $dp['id'] ?>"><?= ucfirst($dp['nama_grup']) ?></option>
            <?php } ?>
        </select>
        <div class="form-control-icon">
            <i class="bi bi-people"></i>
        </div>
    </div>

    <div class="form-group position-relative has-icon-left mb-4">
        <input type="password" name="pass1" id="pass1" class="validate[required,minSize[8]] form-control form-control-xl" placeholder="Password">
        <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
    </div>

    <div class="form-group position-relative has-icon-left mb-4">
        <input type="password" name="pass2" id="pass2" class="validate[required,equals[pass1]] form-control form-control-xl" placeholder="Confirm Password">
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

    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" id="btn-daftar">Daftar Sekarang</button>
</form>
<div class="text-center mt-5 text-lg fs-4">
    <p class="text-gray-600 loadin">Sudah ada akunta? <a href="<?= base_url('login') ?>" class="font-bold">Masuk</a>.</p>
    <p class="text-gray-600"><a href="<?= base_url() ?>" class="font-bold">Website <?= $this->config->item('app_singkatan') ?></a>.</p>
</div>