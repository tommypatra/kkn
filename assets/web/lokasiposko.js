var map = L.map('map').setView([vLatitude, vLongitude], 8);
L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

loadposko();

function loadposko() {
    let cari= {
        0:{val: $("#idkkn").val(),fld: "k.id",cond: "where"},
        //1:{val: null,fld: "(p.latitude IS NOT NULL AND p.longitude IS NOT NULL)",cond: "where"},
    };
    appAjax("api/dataposko", cari).done(function(vRet) {  
        if(vRet.status){
            let tmp=vRet.db[0];
            $("#tema").html(tmp.tema+" ("+tmp.tahun+")");
            $.each(vRet.db, function (key, db) {
                urlposko=vBase_url+db['path']+"?"+Math.random()*100;
            
                if(db['latitude'] && db['longitude']){
                    let marker = L.marker([db['latitude'], db['longitude']]).addTo(map);
                    
                    let caption_marker="<div style='align-text:center;' align='center'><a href='"+vBase_url+"dashboard/kelompok/"+db['idkelompok']+"'>";                                
                    caption_marker=caption_marker+"<img src='"+urlposko+"' width='100%'><br>";
                    caption_marker=caption_marker+"<b>Kelompok "+db['namakelompok']+"</b><br>";
                    caption_marker=caption_marker+" "+db.provinsi+" "+db.kabupaten+" "+db.kecamatan+" "+db.desa+" ";
                    caption_marker=caption_marker+"<hr><b>DPL : "+db.nama+"</b>";
                    caption_marker=caption_marker+"</a></div>";
                    marker.bindPopup(caption_marker);
                }
            });
        }
    });
};