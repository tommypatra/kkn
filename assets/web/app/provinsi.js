var dtTable = null;
loadTabel();

//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//tambahan - mengecek semua ceklist
$("#idprovinsi").change(function () {
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
            "url": vBase_url + "app/provinsi/read",
            "dataType": "json",
            "type": "POST",
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [3, "asc"],
            [2, "asc"],
        ],
        "columns": [{
            "data": "cek",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "no",
            "width": "10%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "provinsi",
            "width": "80%",
        },
        {
            "data": "kode",
            "width": "10%",
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
    $("#formweb #kode").val("");
    $("#formweb #provinsi").val("");
}

//menampilkan form modal
function tambahForm() {

    var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    //refreshidparent();
    resetform();
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
        provinsi: $("#formweb #provinsi").val(),
        kode: $("#formweb #kode").val(),
    };
    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/provinsi/simpan", formVal).done(function(vRet) {

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
        vTable:"wilayah_prov as w",
        vField:"w.*",
        vCari: {
            0:{ val: $(this).data("id"), fld: "w.id", cond: "where" },
        }
    };
        
    appAjax("api/carigeneral", formVal).done(function(dataLoad) {
        if (dataLoad.status) {
            let dtweb=dataLoad.db[0];
            //console.log(dtweb);
            $("#formweb #id").val(dtweb.id);
            $("#formweb #kode").val(dtweb.kode);
            $("#formweb #provinsi").val(dtweb.provinsi);
                    
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
    fDelete(submitVal, "app/provinsi/delete");
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
        fDelete(submitVal, "app/provinsi/delete");
    }
})

//tambahan - refresh datatables
$(".refreshData").click(function() {
    loadTabel();    
});