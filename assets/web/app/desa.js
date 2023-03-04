var dtTable = null;

loadprovinsi();
loadTabel();

let datainitiate = [{ id: "", text: "- pilih -" }];
sel2_datalokal("#idkabupaten", datainitiate);
sel2_datalokal("#idkecamatan", datainitiate);


function loadprovinsi() {
    let formVal = {
        vTable: "wilayah_prov",
        vOrder: "provinsi ASC",
        vField: "*",
        vCari: {},
    };

    $('#idprovinsi').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['provinsi'] });
            });
        }
        sel2_datalokal("#idprovinsi", dataparent);
    });
}

function loadkabupaten() {
    let formVal = {
        vTable: "wilayah_kab",
        vOrder: "kabupaten ASC",
        vField: "*",
        vCari: {
            0:{ val: $("#idprovinsi").val(), fld: "idprovinsi", cond: "where" },
        }
    };

    $('#idkabupaten').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['kabupaten'] });
            });
        }
        sel2_datalokal("#idkabupaten", dataparent);
    });
}

function loadkecamatan() {
    let formVal = {
        vTable: "wilayah_kec",
        vOrder: "kecamatan ASC",
        vField: "*",
        vCari: {
            0:{ val: $("#idkabupaten").val(), fld: "idkabupaten", cond: "where" },
        }
    };

    $('#idkecamatan').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['kecamatan'] });
            });
        }
        sel2_datalokal("#idkecamatan", dataparent);
    });
}
//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//tambahan - mengecek semua ceklist
$("#idprovinsi").change(function () {
    $('#idkabupaten').empty().trigger("change");
    if($("#idprovinsi").val()!="")
        loadkabupaten();
});

//tambahan - mengecek semua ceklist
$("#idkabupaten").change(function () {
    $('#idkecamatan').empty().trigger("change");
    if($("#idkabupaten").val()!="")
        loadkecamatan();
    loadTabel();
});

//tambahan - mengecek semua ceklist
$("#idkecamatan").change(function () {
    loadTabel();
});

//datatables, menampilkan data
function loadTabel() {
    dtTable = $('.table').DataTable({
        "autoWidth": false,
        "bDestroy": true,
        "processing": false,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 75, -1],
            ["25", "50", "75", "Semua"]
        ],
        "ajax": {
            "url": vBase_url + "app/desa/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idprovinsi = $("#idprovinsi").val();
                d.idkabupaten = $("#idkabupaten").val();
                d.idkecamatan = $("#idkecamatan").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [2, "asc"],
            [3, "asc"],
        ],
        "columns": [{
            "data": "cek",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "kode",
            "width": "10%",
        },
        {
            "data": "desa",
            "width": "70%",
        },
        {
            "data": "aksi",
            "orderable": false,
            "searchable": false
        },
        ],
        initComplete: function (e) {
            var api = this.api();
            $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function (e) {
                if (e.keyCode == 13) {
                    api.search(this.value).draw();
                }
            });
        },
    });
}

function resetform(){
    $("#formweb #id").val("");
    $("#formweb #desa").val("");
    $("#formweb #kodewilayah_prov").val("");
    $("#formweb #kodewilayah_kab").val("");
    $("#formweb #kodewilayah_kec").val("");
    $("#formweb #kode").val("");
}

//menampilkan form modal
function tambahForm() {
    if($("#idprovinsi").val()=="" || $("#idkabupaten").val()=="" || $("#idkecamatan").val()=="" ){
        showNotification(false,["Pilih provinsi, kabupaten dan kecamatan terlebih dahulu"]);    
    }else{
        var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal.toggle();
        //refreshidparent();
        resetform();
    }
}


$(".btnBatal").click(function() {
    resetform();
});

$(".addPage").click(function() {
    tambahForm();
});

//simpan data dari form modal 
$("#formweb").submit(function(e) {
    e.preventDefault();
    let formVal = {
            id: $("#formweb #id").val(),
            kodewilayah_kab: $("#formweb #kodewilayah_kab").val(),
            kodewilayah_prov: $("#formweb #kodewilayah_prov").val(),
            kodewilayah_kec: $("#formweb #kodewilayah_kec").val(),
            kode: $("#formweb #kode").val(),
            desa: $("#formweb #desa").val(),
            idkecamatan: $("#idkecamatan").val(),
    };
    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/desa/simpan", formVal).done(function(vRet) {

            let bgclr = "bg-red";
            if (vRet.status) {
                bgclr = "bg-blue";
                if ($("#formweb #id").val() == "")
                    resetform();
                dtTable.ajax.reload(null, false);
                //dataTree();
                //refreshidparent();
            }
            showNotification(vRet.status,vRet.pesan);    
        });
    }
    return false;
});

//fungsi menghapus data 
function fDelete(submitVal, vUrl) {
    if ($("#idPilih").val() !== "")
        if (confirm("Apakah anda yakin? data akan terhapus secara permanen...")) {
            appAjax(vUrl, submitVal).done(function(vRet) {
                if (vRet.status) {
                    dtTable.ajax.reload(null, false);
                    //dataTree();
                    //refreshidparent();
                    resetform();
                }
                showNotification(vRet.status,vRet.pesan);    
            });
        }
}

//ganti / edit data  	
$(document).on("click",".editRow", function(e) {
    e.preventDefault();

    let formVal = {
        vTable:"wilayah_desa",
        vField:"*",
        vCari: {
            0:{ val: $(this).data("id"), fld: "id", cond: "where" },
        }
    };
        
    appAjax("api/carigeneral", formVal).done(function(dataLoad) {
        if (dataLoad.status) {
            let dtweb=dataLoad.db[0];
            //console.log(dtweb);

            $("#formweb #id").val(dtweb.id);
            $("#formweb #kodewilayah_kab").val(dtweb.kodewilayah_kab);
            $("#formweb #kodewilayah_prov").val(dtweb.kodewilayah_prov);
            $("#formweb #kodewilayah_kec").val(dtweb.kodewilayah_kec);
            $("#formweb #kode").val(dtweb.kode);
            $("#formweb #desa").val(dtweb.desa);
                    
            var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal.toggle();
        
        }
    });
});

//menghapus data dari tombol hapus datatables
$(document).on("click",".deleteRow", function(e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = {
        idTerpilih,
    }
    fDelete(submitVal, "app/desa/delete");
});

//menghapus banyak data dari ceklist datatables 	
$(".hapusTerpilih").click(function () {
    let idTerpilih = [];
    $('.cekbaris').each(function (i) {
        if ($(this).is(':checked')) {
            idTerpilih.push($(this).val());
        }
    });
    if (idTerpilih.length > 0) {
        let submitVal = { "idTerpilih": idTerpilih };
        fDelete(submitVal, "app/desa/delete");
    }
})

//tambahan - refresh datatables
$(".refreshData").click(function() {
    loadTabel();    
});

