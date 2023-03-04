var total_data=0;
loaddata();

//menampilkan data simulasi
function loaddata() {
    let vCari = {idkkn: $("#idkkn").val()};
    $("#data-simulasi").html("");
    appAjax("app/autopembagian/datasimulasi", vCari).done(function (vRet) {
        if (vRet.status) {
            $("#data-simulasi").html(vRet.html);
            total_data=vRet.totalpeserta;
            //console.log(vRet.db)
        //loadoutput(vRet.db[0]);
        }
    });
}

$(".refreshData").click(function(){
    loaddata();
});

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

$("#fpembagian").submit(function (e) {
    e.preventDefault();
    let formVal= $(this).serialize();
    if (confirm("Apakah anda yakin?")) {
        proses(0);
    }
});

function proses(index){
    let vElement=".simulasi-"+index;
    let vCheck=vElement+" .cekbaris";
    if ($(vCheck).is(':checked')) {
        let formVal= {
            idelement:$(vCheck).data("idelement"),
            idkelompok:$(vCheck).data("idkelompok"),
            idjabatan:"6",
            idpeserta:$(vCheck).val(),
        };        
        appAjax("app/autopembagian/simpan", formVal).done(function (vRet) {
            $("#"+vRet.idelement).html("<i class='bi bi-check2-circle'></i>");
        });
    }

    if(index<(total_data-1)){
        index++;
        proses(index);
    }
}

