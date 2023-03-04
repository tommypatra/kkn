cariprofil();

if($('#editorberita').length){
    var quill = new Quill('#editorberita', {
        placeholder: 'berita anda disini...',
        theme: 'snow'    
    });

    
}

//ganti dari datatable
function cariprofil() {
    let formVal= {
        idjenis_profil:$("#idjenis_profil").val()
    };     
    appAjax("app/profil/cari", formVal).done(function (vRet) {
        if (vRet.status) {
            loadoutput(vRet.db[0]);
        }
    });
}

//untuk ganti, loadlokasi dari paramater array db
function loadoutput(db) {
    resetform();
    $("#idprofil").val(db['id']);
    $(".ql-editor").html(db['detail']);
    if(db['thumbnail']){
        let vimg=vBase_url+db['thumbnail']+"?"+Math.random()*100;
        $("#imgpreview").html("<img src='"+vimg+"' width='100%'>");
    }
};

//bersihkan validasi
$(document).on("click keypress", "#formprofil input", function (e) {
    $(this).validationEngine('hideAll');
});

//simpan
$("#formprofil").submit(function (e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('idprofil', $("#idprofil").val()); 
    formData.append('idjenis_profil', $("#idjenis_profil").val()); 
    formData.append('file', $('#path').get(0).files[0]); 
    formData.append('detail', $(".ql-editor").html()); 
    if ($(this).validationEngine('validate')) {
        $.ajax({
            url : vBase_url+"app/profil/simpan",
            type : "post",
            dataType : "json",
            data : formData,                         
            cache : false,
            contentType : false,
            processData : false,
            success : function(vRet){
                if(vRet.status){
                    cariprofil();
                }
                showNotification(vRet.status, vRet.pesan);
            }
        });
    }
});

//fungsi refresh
$(".refreshData").click(function () {
    cariprofil();
});

//reset nilai form
function resetform() {
    $("#idprofil").val("");
    $("#formprofil")[0].reset();
    $(".ql-editor").html("");
    $("#imgpreview").html("");
}