var dtTable = null;

loadTabel();
sel2_aktif("#self_activated","#modal-form-web");
sel2_aktif("#reg","#modal-form-web");
sel2_aktif("#aktif","#modal-form-web");


//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//tambahan - mengecek semua ceklist
$("#idgrup").change(function () {
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
            "url": vBase_url + "app/grup/read",
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
            "data": "nama_grup",
            "width": "20%",
        },
        {
            "data": "tableref",
            "width": "20%",
        },
        {
            "data": "self_activated",
            "width": "8%",
        },
        {
            "data": "reg",
            "width": "8%",
        },
        {
            "data": "ket",
            "width": "10%",
        },
        {
            "data": "aktif",
            "width": "8%",
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
    $("#formweb #nama_grup").val("");
    $("#formweb #tableref").val("");
    $("#formweb #ket").val("");
    $('#formweb #self_activated').val("y").trigger("change");
    $('#formweb #reg').val("y").trigger("change");
    $('#formweb #aktif').val("y").trigger("change");
    $('#formweb #idmodule').val("").trigger("change");
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
        nama_grup: $("#formweb #nama_grup").val(),
        tableref: $("#formweb #tableref").val(),
        self_activated: $("#formweb #self_activated").val(),
        reg: $("#formweb #reg").val(),
        aktif: $("#formweb #aktif").val(),
        ket: $("#formweb #ket").val(),
    };
    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/grup/simpan", formVal).done(function(vRet) {

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
        vTable:"grup as g",
        vField:"g.*",
        vCari: {
            0:{ val: $(this).data("id"), fld: "g.id", cond: "where" },
        }
    };
        
    appAjax("api/carigeneral", formVal).done(function(dataLoad) {
        if (dataLoad.status) {
            let dtweb=dataLoad.db[0];
            //console.log(dtweb);
            $("#formweb #id").val(dtweb.id);
            $("#formweb #nama_grup").val(dtweb.nama_grup);
            $("#formweb #tableref").val(dtweb.tableref);
            $("#formweb #ket").val(dtweb.ket);
            $('#formweb #self_activated').val(dtweb.self_activated).trigger("change");
            $('#formweb #reg').val(dtweb.reg).trigger("change");
            $('#formweb #aktif').val(dtweb.aktif).trigger("change");
                    
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
    fDelete(submitVal, "app/grup/delete");
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
        fDelete(submitVal, "app/grup/delete");
    }
})

//tambahan - refresh datatables
$(".refreshData").click(function() {
    loadTabel();    
});

