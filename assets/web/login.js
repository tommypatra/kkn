//tidak boleh enter
/*
$('#formlogin input').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
*/

function refreshhitung(vlabel="Hitung"){
  $("#fldHitung").attr("placeholder","Hitung "+vlabel);
  $("#fldHitung").val("");
}

//bersihkan validasi
$(document).on("click keypress","#formlogin input",function(e) {
  $(this).validationEngine('hideAll');
});

//ketika submit
$(".btn-lupapass").click(function(e) {
  e.preventDefault();
  $('#formlupapass')[0].reset();
  var myModal = new bootstrap.Modal(document.getElementById('modal-form-lupapass'), {
    backdrop: 'static',
    keyboard: false,
  });
  myModal.toggle();
});

//simpan jadwal
$("#formlupapass").submit(function (e) {
  e.preventDefault();
  let formVal = $(this).serialize();
  if ($(this).validationEngine('validate')) {
      appAjax("resetpass/kirimlink", formVal).done(function (vRet) {
          showNotification(vRet.status, vRet.pesan);
          if (vRet.status) {
            $('#formlupapass')[0].reset();
          }
      });
  }
});

//ketika submit
$("#login").click(function(e) {
  e.preventDefault();
  if ($("#formlogin").validationEngine('validate')) {
    let formVal = $("#formlogin").serialize();
    appAjax("login/cek_new", formVal).done(function(vRet) {
      if (vRet.status) {
        //pesan sukses timer
        //$("#formlogin :input").attr("disabled", true);
        let timerInterval;
        Swal.fire({
          title: 'Login Berhasil!',
          html: 'Anda akan di arahkan secara otomatis dalam <b></b> milliseconds, silahkan menunggu',
          timer: 2000,
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
            location.href = "app/dashboard";
          }
        })  
        
      //ketika return false        
      }else{
        showNotification(vRet.status,vRet.pesan);
        refreshhitung(vRet.calculate.v1+"+"+vRet.calculate.v2);
      }
    });

  }
});