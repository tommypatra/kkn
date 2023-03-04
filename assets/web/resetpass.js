
//simpan jadwal
$("#formpass").submit(function (e) {
  e.preventDefault();
  let formVal = $(this).serialize();
  if ($(this).validationEngine('validate') && confirm("Apakah anda yakin?")) {
      appAjax("resetpass/simpan", formVal).done(function (vRet) {
          showNotification(vRet.status, vRet.pesan);
          if (vRet.status) {
            let timerInterval;
            Swal.fire({
              title: 'Ganti password berhasil!',
              html: 'Anda akan di arahkan secara otomatis dalam <b></b> milliseconds, silahkan menunggu',
              timer: 1500,
              icon: 'success',
              allowOutsideClick: false,
              timerProgressBar: true,
              didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
                timerInterval = setInterval(() => {
                  b.textContent = Swal.getTimerLeft()
                }, 100)
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
            }).then((result) => {
              if (result.dismiss === Swal.DismissReason.timer) {
                location.href = vBase_url+"login";
              }
            })            
          }
      });
  }
});

//bersihkan validasi
$(document).on("click keypress","#formpass input",function(e) {
  $(this).validationEngine('hideAll');
});
