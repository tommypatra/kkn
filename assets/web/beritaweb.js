$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
        let lastid = $(".rowdaftar:last").data("id");
        loadweb(lastid);
    }
});

loadweb(0);

$("#loadMore").click(function(){
    let lastid = $(".rowdaftar:last").data("id");
    loadweb(lastid);
});

$("#cari").keypress(function(e) {
    if(e.which == 13) {
        loadweb(0);
    }
});

function loadweb(lastid,limit=null){
    let formVal={
        "lastid":lastid,
        "limit":limit,
        "vCari": { 
            0: { cond: 'like', val: $("#cari").val(), fld: 'b.judul' },
         },
    }
    if(lastid<1)
        $("#daftar").empty();

    if(lastid>=0)
        appAjax("berita/loadweb", formVal).done(function(vRet) {        
            if(vRet.status){
                $("#loadMore").show();        
                jQuery.each(vRet.db, function(index, item) {
                    $("#daftar").append(item);
                });                    
            }else{
                $("#loadMore").hide();        
            }
        });
}

function refresh(){
    let jumlkh = $('.rowdaftar').length;
    $("#daftar").empty();
    loadlkh(0,jumlkh);
}