var latitude = $("#map").data("latitude"); 
var longitude = $("#map").data("longitude");

var map = L.map('map').setView([-4.008333, 119.629185], 8);
L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

$("#loadMoreLKH").hide();

if(latitude && longitude){                
    let marker = L.marker([latitude, longitude]).addTo(map);
    let caption_marker="Lokasi Posko";                
    marker.bindPopup(caption_marker).openPopup();
}

$("#loadMoreLKH").hide();        
$("#card-testimoni").hide();

$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
        let lastid = $(".rowlkh:last").data("id");
        loadlkh(lastid);
    }
});

loadlkh(0);
loadtestimoni();

$("#loadMoreLKH").click(function(){
    let lastid = $(".rowlkh:last").data("id");
    loadlkh(lastid);
});

useraktif();

$("#reload_testimoni").click(function(){
    loadtestimoni();
});

function loadtestimoni(){
    let formVal={idkelompok: $("#idkelompok").val(),hapus:1}
    let vElmt="#daftar_testimoni";
    $(vElmt).empty();
    $("#card_testimoni").hide();
    appAjax("api/readtestimoni", formVal).done(function(vRet) {
        if(vRet.status){
            // jQuery.each(vRet.db, function(index, item) {
            //     $(vElmt).append(item.html);
            // });             
            $(vElmt).append(vRet.html);
            $("#card_testimoni").show();
        }        
    });
}

function loadlkh(lastid,limit=null){
    let formVal={
        "lastid":lastid,
        "limit":limit,
        "vCari": { 
            0: { cond: 'where', val: $("#idkelompok").val(), fld: 'kl.id' },
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


$(document).on("click",".hapus-testimoni",function(e) {
    e.preventDefault();
    let formVal = {idTerpilih:$(this).data('id')};
    if(confirm("apakah anda yakin?")){
        appAjax("mahasiswa/testimoni/delete", formVal).done(function(vRet) {        
            showNotification(vRet.status, vRet.pesan);
            if(vRet.status)
                loadtestimoni();
        });    
    }
});

$(document).on("click",".tambah-like",function(e) {
    e.preventDefault();
    let formVal = {idaktifitas:$(this).data('idaktifitas')};
    if(confirm("yakin kita mau kasi like? tidak bisami dibatalkan itu...")){
        appAjax("dashboard/simpan_like", formVal).done(function(vRet) {        
            showNotification(vRet.status, vRet.pesan);
            if(vRet.status)
                refreshlkh();
        });    
    }
});


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

$(".btn-testimoni").click(function(e){
    e.preventDefault();
    var myModal = new bootstrap.Modal(document.getElementById('modal-testimoni'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    $("#link").val("");
});

$("#formtestimoni").submit(function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    if ($('#formtestimoni').validationEngine('validate')) {
        appAjax("mahasiswa/testimoni/simpan", formVal).done(function(vRet) {
            if (vRet.status) {
                loadtestimoni();
            }
            showNotification(vRet.status,vRet.pesan);    
        });
        //alert("OK");
    }
    return false;
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
        0:{val: $("#idkelompok").val(),fld: "kl.id",cond: "where"},
    };
    appAjax("api/aktifitas_terbaik", cari).done(function(vRet) {  
        if(vRet.status){
            $(".detail-aktifitas-populer").html(vRet.html);
        }      
    });    
})