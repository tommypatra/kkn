useraktif();

loadlkh();

function resetform_komentar(){
    $(".komentar").val("");
}

$(document).on("submit",".fkomentar",function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    appAjax("dashboard/simpan_komentar", formVal).done(function(vRet) {        
        showNotification(vRet.status, vRet.pesan);
        resetform_komentar();
        loadlkh();
    });
});

$(document).on('click','.gambardet',function(){
    var gbr=$(this).prop('src');
    window.open(gbr, 'newwindow', 'width=500, height=500');
});

function loadlkh(){
    let formVal={
        "modeinput":false,
        "idaktifitas":$("#idaktifitas").val(),
    }
    appAjax("dashboard/read_aktifitas", formVal).done(function(vRet) {        
        $("#daftarlkh").html(vRet.html);
    });
}

function refreshlkh(){
    $("#daftarlkh").empty();
    loadlkh();
}
