var dtTable = null;

loadfakultas();
loadTabel();

function loadfakultas() {
    let formVal = {
        vTable: "mst_fakultas as f",
        vOrder: "f.fakultas ASC",
        vField: "f.*",
        vCari: {},
    };

    $('#idfakultas').empty().trigger("change");
    appAjax("api/carigeneral", formVal).done(function (vRet) {
        //refresh data parent
        let dataparent = [{ id: "", text: "" }];
        if (vRet.status) {
            jQuery.each(vRet.db, function (i, val) {
                dataparent.push({ id: val['id'], text: val['fakultas'] });
            });
        }
        sel2_datalokal("#idfakultas", dataparent);
    });
}


//tambahan - mengecek semua ceklist
$(".cekSemua").change(function () {
    $(".cekbaris").prop('checked', $(this).prop("checked"));
});

//tambahan - mengecek semua ceklist
$("#idfakultas").change(function () {
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
            "url": vBase_url + "app/prodi/read",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idfakultas = $("#idfakultas").val();
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
            "data": "fakultas",
            "width": "30%",
        },
        {
            "data": "prodi",
            "width": "50%",
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
    $("#formweb #prodi").val("");
}

//menampilkan form modal
function tambahForm() {
    if($("#idfakultas").val()==""){
        showNotification(false,["Pilih fakultas terlebih dahulu"]);    
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
            prodi: $("#formweb #prodi").val(),
            idfakultas: $("#idfakultas").val(),
    };
    if ($('#formweb').validationEngine('validate')) {
        appAjax("app/prodi/simpan", formVal).done(function(vRet) {

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

    if($("#idfakultas").val()==""){
        showNotification(false,["Pilih fakultas terlebih dahulu"]);    
    }else{
        let formVal = {
            vTable:"mst_prodi as p",
            vField:"p.*",
            vCari: {
                0:{ val: $(this).data("id"), fld: "p.id", cond: "where" },
            }
        };
            
        appAjax("api/carigeneral", formVal).done(function(dataLoad) {
            if (dataLoad.status) {
                let dtweb=dataLoad.db[0];
                //console.log(dtweb);

                $("#formweb #id").val(dtweb.id);
                $("#formweb #prodi").val(dtweb.prodi);
                        
                var myModal = new bootstrap.Modal(document.getElementById('modal-form-web'), {
                    backdrop: 'static',
                    keyboard: false,
                });
                myModal.toggle();
            
            }
        });
    }
});

//menghapus data dari tombol hapus datatables
$(document).on("click",".deleteRow", function(e) {
    e.preventDefault();
    let idTerpilih = [$(this).data("id")];
    let submitVal = {
        idTerpilih,
    }
    fDelete(submitVal, "app/prodi/delete");
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
        fDelete(submitVal, "app/prodi/delete");
    }
})

//tambahan - refresh datatables
$(".refreshData").click(function() {
    loadTabel();    
});

