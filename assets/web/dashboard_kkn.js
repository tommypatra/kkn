var latitude = null; 
var longitude = null;
$("#loadMoreLKH").hide();        

$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
        let lastid = $(".rowlkh:last").data("id");
        loadlkh(lastid);
    }
});

loadlkh(0);

$("#loadMoreLKH").click(function(){
    let lastid = $(".rowlkh:last").data("id");
    loadlkh(lastid);
});

function loadlkh(lastid,limit=null){
    let formVal={
        "lastid":lastid,
        "limit":limit,
        "vCari": { 
            0: { cond: 'where', val: $("#idkkn").val(), fld: 'k.id' },
         },
    }
    if(lastid<1){
        $("#daftarlkh").empty();
    }
    
    if(lastid>=0)
        appAjax("dashboard/loadlkh", formVal).done(function(vRet) {        
            if(vRet.status){
                $("#loadMoreLKH").show();        
                jQuery.each(vRet.db, function(index, item) {
                    $("#daftarlkh").append(item);
                });                    
            }else{
                if(lastid<1){
                    $("#daftarlkh").append(vRet.db[0]);
                    //$("#daftarlkh").html("LKH TIDAK DITEMUKAN");
                }
                $("#loadMoreLKH").hide();        
            }
        });
}

$(document).on('click','.gambardet',function(){
    var gbr=$(this).prop('src');
    window.open(gbr, 'newwindow', 'width=500, height=500');
});

useraktif();

$("#loadMoreLKH").click(function(){
    let lastid = $(".rowlkh:last").data("id");
    loadlkh(lastid);
});

function refreshlkh(){
    let jumlkh = $('.rowlkh').length;
    $("#daftarlkh").empty();
    loadlkh(0,jumlkh);
}

function resetform_komentar(){
    $(".komentar").val("");
}

$(document).on("submit",".fkomentar",function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    appAjax("dashboard/simpan_komentar", formVal).done(function(vRet) {        
        showNotification(vRet.status, vRet.pesan);
        resetform_komentar();
        refreshlkh();
    });
});


$(".act-aktifitas").click(function(e){
    e.preventDefault();
    let vtitle=$(this).html();
    let vkategori=$(this).data("kategori");
    $("#modal-aktifitas .modal-title").html($(this).html());
    var myModal = new bootstrap.Modal(document.getElementById('modal-aktifitas'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    let cari= {
        title:vtitle,
        kategori:vkategori,
        0:{val: $("#idkkn").val(),fld: "k.id",cond: "where"},
    };
    appAjax("api/aktifitas_terbaik", cari).done(function(vRet) {  
        if(vRet.status){
            $(".detail-aktifitas-populer").html(vRet.html);
        }      
    });    
});

$(".act-kelompok").click(function(e){
    e.preventDefault();
    let vtitle="Kelompok Teraktif";
    $("#modal-aktifitas .modal-title").html($(this).html());
    var myModal = new bootstrap.Modal(document.getElementById('modal-aktifitas'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    let cari= {
        title:vtitle,
        0:{val: $("#idkkn").val(),fld: "k.id",cond: "where"},
    };
    appAjax("api/kelompok_terkaktif", cari).done(function(vRet) {  
        if(vRet.status){
            $(".detail-aktifitas-populer").html(vRet.html);
        }      
    });    
});