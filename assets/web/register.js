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


$("#showloading").click(function(e) {
  $("body").addClass("loading");
});

$("#hideloading").click(function(e) {
  $("body").removeClass("loading");		
});

function refreshhitung(vlabel="Hitung"){
  $("#fldHitung").attr("placeholder","Hitung "+vlabel);
  $("#fldHitung").val("");
}

//bersihkan validasi
$(document).on("click keypress","#formregister input",function(e) {
  $(this).validationEngine('hideAll');
});

//ketika submit
$("#btn-daftar").click(function(e) {
  e.preventDefault();
  if ($("#formregister").validationEngine('validate')) {
    let formVal = $("#formregister").serialize();
    appAjax("daftar/simpan", formVal).done(function(vRet) {
      if (vRet.status) {
        //pesan sukses timer
        //$("#formlogin :input").attr("disabled", true);
        let email = vRet.email;
        let timerInterval;
        Swal.fire({
          title: 'Pendaftaran Berhasil!',
          html: 'Pendaftaran berhasil dilakukan, berikutnya silahkan aktivasi pada link yang telah dikirimkan ke email '+email+' dan update data anda',
          icon: 'success',
        }).then((result) => {
            location.href = 'login';
        })  
      //ketika return false        
      }else{
        showNotification(vRet.status,vRet.pesan);
        refreshhitung(vRet.calculate.v1+"+"+vRet.calculate.v2);
      }
    });

  }
});